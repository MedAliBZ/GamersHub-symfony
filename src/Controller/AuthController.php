<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
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

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'user' => $this->getUser()]);
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
        $user->setBirthDate(\DateTime::createFromFormat('d/m/Y', '21/08/2000'));
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form["confirmPassword"]->getData() != $form["password"]->getData()) {
                return $this->render('security/register.html.twig', [
                    "signupForm" => $form->createView(),
                    "error" => "Passwords do not match!"
                ]);
            } else {
                $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
                $user->setCoins(0);
                $user->setRoles(['ROLE_USER']);
                date_default_timezone_set('Europe/Paris');
                $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
                $user->setCreatedAt($dateTime);
                $user->setLastUpdated($dateTime);
                $user->setIsEnabled(true);
                $user->setIsVerified(false);
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
