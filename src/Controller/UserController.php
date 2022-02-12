<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Form\UpdateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/delete", name="deleteProfile")
     */
    public function delete(Request $req): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $this->get('security.token_storage')->setToken(null);
        $req->getSession()->invalidate();

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute("app_login");
    }

    /**
     * @Route("/profile/updateInfo", name="updateProfileInfo")
     */
    public function updateProfile(Request $request){
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $oldPassword = $user->getPassword();
            if (!password_verify($request->request->get('update_user')["oldPassword"], $oldPassword)) {
                return $this->render('user/updateProfileInfos.html.twig', [
                    "updateForm" => $form->createView(),
                    "error" => "wrong pass"
                ]);
            } else {
                date_default_timezone_set('Europe/Paris');
                $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
                $user->setLastUpdated($dateTime);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
        }
        return $this->render("user/updateProfileInfos.html.twig", [
            "updateForm" => $form->createView(),
            "user"=>$user
        ]);
    }

    /**
     * @Route("/updatePass", name="updatePass")
     */
    public function passUpdate(Request $request): Response
    {
        $user = $this->getUser();
        $newUser = new User();
        $form = $this->createForm(UpdatePasswordType::class,$newUser);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $oldPassword = $user->getPassword();
            if (!password_verify($request->request->get('update_password')["oldPassword"], $oldPassword)) {
                return $this->render('user/updatePassword.html.twig', [
                    "updatePassForm" => $form->createView(),
                    "error" => "wrong pass"
                ]);
            } else if ($request->request->get('update_password')["confirmPassword"] != $request->request->get('update_password')["password"]) {
                return $this->render('user/updatePassword.html.twig', [
                    "updatePassForm" => $form->createView(),
                    "error" => "Passwords are not the same!"
                ]);
            } else {
                date_default_timezone_set('Europe/Paris');
                $dateTime = date_create_immutable_from_format('m/d/Y H:i:s', date('m/d/Y H:i:s', time()));
                $user->setLastUpdated($dateTime);
                $user->setPassword(password_hash($newUser->getPassword(), PASSWORD_DEFAULT));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute("profile");
            }
        }
        return $this->render("user/updatePassword.html.twig", [
            "updatePassForm" => $form->createView(),
            "user" => $user,
        ]);
    }

    /**
     * @Route("/admin/users", name="showUsers")
     */
    public function showUsers(): Response
    {
        $repo =$this->getDoctrine()->getRepository(User::class);
        return $this->render("user/usersBack.html.twig", [
            'user'=>$this->getUser(),
            'usersList'=>$repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="deleteUser")
     */
    public function deleteUser(User $usr): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($usr);
        $em->flush();

        $repo =$this->getDoctrine()->getRepository(User::class);
        return $this->redirectToRoute("showUsers");
    }
    /**
     * @Route("/admin/users/{id}/update", name="updateUser")
     */
    public function updateUser(User $user): Response{
        return $this->redirectToRoute("showUsers");
    }

}
