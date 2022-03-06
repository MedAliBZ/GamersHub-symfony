<?php

namespace App\Controller;

use App\Entity\MissionsDone;
use App\Entity\User;
use App\Form\UpdatePasswordType;
use App\Form\UpdateUserBackType;
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

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $missionsDone = $this->getDoctrine()->getRepository(MissionsDone::class)->findDoneMissions($user->getUsername());

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'missionsDone' => $missionsDone
        ]);
    }

    /**
     * @Route("/profile/delete", name="deleteProfile")
     */
    public function delete(Request $req): Response
    {
        $user = $this->getUser();
        if (!$user) {
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
    public function updateProfile(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("profile");
        }
        return $this->render("user/updateProfileInfos.html.twig", [
            "updateForm" => $form->createView(),
            "user" => $user
        ]);
    }

    /**
     * @Route("/updatePass", name="updatePass")
     */
    public function passUpdate(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdatePasswordType::class, $this->getUser());
        $oldPassword = $user->getPassword();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            if (!password_verify($request->request->get('update_password')["oldPassword"], $oldPassword)) {

                return $this->render('user/updatePassword.html.twig', [
                    "updatePassForm" => $form->createView(),
                    "error" => "Wrong Password!",
                    "user" => $user
                ]);
            } else {
                $user->setPassword(password_hash($form['password']->getData(), PASSWORD_DEFAULT));
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute("profile");
            }
        }
        return $this->render("user/updatePassword.html.twig", [
            "updatePassForm" => $form->createView(),
            "user" => $this->getUser(),
        ]);
    }

    /**
     * @Route("/admin/users", name="showUsers")
     */
    public function showUsers(): Response
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        return $this->render("user/usersBack.html.twig", [
            'user' => $this->getUser(),
            'usersList' => $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="deleteUser")
     */
    public function deleteUser(User $usr, Request $req): Response
    {
        if ($usr == $this->getUser()) {
            $this->get('security.token_storage')->setToken(null);
            $req->getSession()->invalidate();
            $em = $this->getDoctrine()->getManager();
            $em->remove($usr);
            $em->flush();
            return $this->redirectToRoute("app_login");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($usr);
        $em->flush();

        return $this->redirectToRoute("showUsers");
    }

    /**
     * @Route("/admin/users/{id}/update", name="updateUser")
     */
    public function updateUser(Request $req, User $user): Response
    {
        $form = $this->createForm(UpdateUserBackType::class, $user);
        if (in_array("ROLE_ADMIN", $user->getRoles())) {
            $form["isAdmin"]->setData(true);
        }
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->getDoctrine()->getRepository(User::class)->findByUsernameDiffId($form["username"]->getData(), $user->getId())) {
                return $this->render('user/userUpdateBack.html.twig', [
                    "form" => $form->createView(),
                    "error" => "Invalid username!",
                    "user" => $this->getUser()
                ]);
            }
            if ($form['isAdmin']->getData() == true) {
                $user->setRoles(["ROLE_ADMIN"]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("showUsers");
        }

        return $this->render("user/userUpdateBack.html.twig", [
            'form' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

}
