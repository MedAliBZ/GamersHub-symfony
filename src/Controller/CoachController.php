<?php

namespace App\Controller;

use App\Entity\Coach;
use App\Form\CoachType;
use App\Form\ContactType;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Scalar\String_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class CoachController extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/coach", name="coach_index", methods={"GET"})
     */

    public function index(CoachRepository $coachRepository): Response
    {
        return $this->render('coach/index.html.twig', [
            'coachs' => $coachRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }



    /**
     * @Route("/coach/new", name="coach_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coach = new Coach();
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coach->setUser($this->getUser());
            $entityManager->persist($coach);
            $entityManager->flush();

            return $this->redirectToRoute('coach_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach/_form.html.twig', [
            'coach' => $coach,
            'user' => $this->getUser(),
            'formcoach' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/coachs", name="showcoachs", methods={"GET"})
     */
    public function show(CoachRepository $coachRepository): Response
    {
        return $this->render('coach/coachBack.html.twig', [
            'coachs' => $coachRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/coach/{id}/edit", name="coach_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Coach $coach, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('coach_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach/_form.html.twig', [
            'coach' => $coach,
            'formcoach' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/coach/{id}/delete", name="coach_delete", methods={"GET"})
     */
    public function delete(Coach $coach): Response
    {
          $em=$this->getDoctrine()->getManager();
          $em->remove($coach);
          $em->flush();
        return $this->redirectToRoute('coach_index');
    }

    /**
     * @Route("/admin/{id}/edit", name="coach_edit_back", methods={"GET", "POST"})
     */
    public function editBack(Request $request, Coach $coach, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoachType::class, $coach);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('showcoachs', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coach/coacheditback.html.twig', [
            'coach' => $coach,
            'formcoachback' => $form->createView(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/admin/{id}/delete", name="coach_delete_back", methods={"GET"})
     */
    public function deleteback(Coach $coach): Response
    {
        $em=$this->getDoctrine()->getManager();
        $em->remove($coach);
        $em->flush();
        return $this->redirectToRoute('showcoachs');
    }

    /**
     * @return Response
     * @Route("/coach/Contact/{id}", name="sendmail")
     */
    public function sendmail(Request $request,CoachRepository $rep,$id)
    { $coach=$rep->find($id);
      $form=$this->createForm(ContactType::class);
      $form->add('Submit', SubmitType::class, ['attr'=>['class'=>'cmn-btn']]);
      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid())
          {  $var = $form->get('message')->getData();

              $email = (new TemplatedEmail())
              ->from('ghub2441@gmail.com')
              ->to('ghub2441@gmail.com')
              ->subject($this->getUser()->getEmail()." [Coaching Request]")

              // path of the Twig template to render
              ->html('<p>'.$var.'</p>');

          ;

              $this->mailer->send($email);
              return $this->redirectToRoute('coach_index');


          }
      return $this->render('coach/contact.html.twig',[
          'formail'=> $form->createView(),
          'user' => $this->getUser()
      ]);
    }
}
