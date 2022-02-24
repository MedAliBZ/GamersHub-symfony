<?php

namespace App\Security;

use App\Controller\AuthController;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class GithubAuthenticator extends SocialAuthenticator
{
    private $router;
    private $clientRegistry;
    private $authController;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, AuthController $authController)
    {
        $this->router = $router;
        $this->clientRegistry = $clientRegistry;
        $this->authController = $authController;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supports(Request $request)
    {
        return 'oauth_check' == $request->attributes->get('_route') && ($request->get('service') == 'github' || $request->get('service') == 'google');
    }

    public function getCredentials(Request $request)
    {
        if($request->get('service') == 'google')
            return $this->fetchAccessToken($this->clientRegistry->getClient('google'));
        else
            return $this->fetchAccessToken($this->getClient());


    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        if(in_array('googleapis',explode('.',$credentials->getValues()["scope"]))){
            $oauthUser = $this->clientRegistry->getClient('google')->fetchUserFromToken($credentials);
            return $this->authController->registerGoogle($oauthUser);
        }
        else{
            $oauthUser = $this->getClient()->fetchUserFromToken($credentials);
            $response = HttpClient::create()->request(
                'GET',
                'https://api.github.com/user/emails',
                [
                    'headers' => [
                        'authorization' => "token {$credentials->getToken()}"
                    ]
                ]
            );
            $emails = json_decode($response->getContent(), true);
            foreach ($emails as $email) {
                if ($email['primary']) {
                    $data = $oauthUser->toArray();
                    $data["email"] = $email["email"];
                    $oauthUser = new GithubResourceOwner($data);
                }
            }
            return $this->authController->registerGithub($oauthUser);
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse('/login');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse('/');
    }

    public function getClient()
    {
        return $this->clientRegistry->getClient('github');
    }
}
