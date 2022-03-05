<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\LazyResponseException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;


/**
 * @Route("/api")
 */
class UserAPIController extends AbstractController
{
    /**
     * @Route("/users", name="api_users", methods={"GET"})
     */
    public function allUsers(NormalizerInterface $normalizer): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonContent = $normalizer->normalize($users, 'json', ['groups' => 'post:read']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function index(Request $request, NormalizerInterface $normalizer): Response
    {
//        $token = $csrfTokenManager->refreshToken('authenticate')->getValue();
        if (!($request->request->get('password') && $request->request->get('username')))
            return new Response(
                '{"error": "Missing username or password or both."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $request->request->get('username'), 'oauth' => false]);
        if ($user == null || !password_verify($request->request->get('password'), $user->getPassword()))
            return new Response(
                '{"error": "Invalid credentials."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        else if (!$user->getIsEnabled()) {
            return new Response(
                '{"error": "This account is locked!"}',
                403, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        }

        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);

    }

    /**
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!($request->request->get('password') && $request->request->get('username') && $request->request->get('email') && $request->request->get('confirmPassword')))
            return new Response(
                '{"error": "Missing username or email or password or confirmPassword."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $request->request->get('username')]);
        $userByEmail = $em->getRepository(User::class)->findOneBy(['email' => $request->request->get('email')]);
        if ($user != null)
            return new Response(
                '{"error": "Username is already taken!"}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        else if($userByEmail != null){
            return new Response(
                '{"error": "This email is already registered!"}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        }
        else if( $request->request->get('password') != $request->request->get('confirmPassword')){
            return new Response(
                '{"error": "Passwords do not match!"}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        }
        else{
            $newUser = new User();
            $newUser->setPassword(password_hash($request->request->get('password'), PASSWORD_DEFAULT))
                ->setEmail($request->request->get('email'))
                ->setUsername($request->request->get('username'))
                ->setCoins(0)
                ->setRoles(['ROLE_USER'])
                ->setIsEnabled(true)
                ->setIsVerified(false)
                ->setOauth(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($newUser);
            $em->flush();
            $jsonContent = $normalizer->normalize($newUser, 'json', ['groups' => 'post:read']);
            return new Response(
                '{"message": "User Created!"}',
                201,
                ['Accept' => 'application/json',
                    'Content-Type' => 'application/json']);
        }
    }

    /**
     * @Route("/user", name="api_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NormalizerInterface $normalizer): Response
    {
        if (!$request->request->get('username'))
            return new Response(
                '{"error": "Missing username."}',
                400, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$request->request->get('username')]);
        if ($user == null)
            return new Response(
                '{"error": "User not found."}',
                401, ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new Response(
            "{\"response\": \"{$request->request->get('username')} deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

//    /**
//     * @Route("/user", name="api_update", methods={"PATCH"})
//     */
//    public function update(Request $request): Response
//    {
//        if (!$request->request->get('username'))
//            return new Response(
//                '{"error": "Missing username."}',
//                403, ['Accept' => 'application/json',
//                'Content-Type' => 'application/json']);
//        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$request->request->get('username')]);
//        if ($user == null)
//            return new Response(
//                '{"error": "User not found."}',
//                401, ['Accept' => 'application/json',
//                'Content-Type' => 'application/json']);
//    }
}
