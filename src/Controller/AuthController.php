<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use PharIo\Manifest\InvalidEmailException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function PHPUnit\Framework\throwException;

class AuthController extends AbstractController
{
    /**
     * @Route("/connect/github", name="connect_github")
     */
    public function connect(ClientRegistry $clientRegistry): Response
    {
        $client = $clientRegistry->getClient('github');
        return $client->redirect(['read:user', 'user:email']);
    }

    public function registerGithub(ResourceOwnerInterface $owner)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->createQueryBuilder('u')
            ->where('u.username = :username')
            ->orWhere('u.email = :email')
            ->setParameter('username', $owner->toArray()['login'])
            ->setParameter('email', $owner->toArray()['email'])
            ->getQuery()
            ->getOneOrNullResult();
        if ($user) {
            if ($user->getOauth() == true)
                return $user;
            else if ($user->getEmail() == $owner->toArray()['email'])
                throw new AuthenticationException('Email is already registered!');
            else if ($user->getUsername() == $owner->toArray()['login'])
                throw new AuthenticationException('Username is already registered!');


        }
        date_default_timezone_set('Europe/Paris');
        $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
        $user = (new User())
            ->setUsername($owner->toArray()['login'])
            ->setEmail($owner->toArray()['email'])
            ->setPassword(password_hash("test", PASSWORD_DEFAULT))
            ->setCoins(0)
            ->setRoles(['ROLE_USER'])
            ->setCreatedAt($dateTime)
            ->setLastUpdated($dateTime)
            ->setIsEnabled(true)
            ->setIsVerified(false)
            ->setOauth(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="register")
     */
    public function signupUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getDoctrine()->getRepository(User::class)->findOneBy(["username" => $form["username"]->getData()])) {
                return $this->render('security/register.html.twig', [
                    "signupForm" => $form->createView(),
                    "error" => "This username is already used!"
                ]);
            } else if ($this->getDoctrine()->getRepository(User::class)->findOneBy(["email" => $form["email"]->getData()])) {
                return $this->render('security/register.html.twig', [
                    "signupForm" => $form->createView(),
                    "error" => "This email is already used!"
                ]);
            } else {
                date_default_timezone_set('Europe/Paris');
                $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
                $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT))
                    ->setCoins(0)
                    ->setRoles(['ROLE_USER'])
                    ->setCreatedAt($dateTime)
                    ->setLastUpdated($dateTime)
                    ->setIsEnabled(true)
                    ->setIsVerified(false)
                    ->setOauth(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect("login");
            }
        }
        return $this->render('security/register.html.twig', [
            "signupForm" => $form->createView(),
        ]);
    }
}
