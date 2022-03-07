<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Entity\Teams;
use App\Form\TeamsType;
use App\Form\TeamsBackType;
use App\Repository\MatchsRepository;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


class TeamsController extends AbstractController
{
    /**
     * @Route("/teams/", name="teams_index")
     */


    public function index(Request $request,TeamsRepository $teamsRepository,PaginatorInterface $paginator): Response
    {

        $repo =$this->getDoctrine()->getRepository(Teams::class)->findBy([],['rank' => 'desc']);
        $team = $paginator->paginate(
            $repo, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );
        return $this->render('teams/Teams.html.twig', [
            'user' => $this->getUser(),

            'teamsList'=> $team
        ]);

    }

    /**
     * @Route("/teams/new", name="teams_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $team = new Teams();
        $form = $this->createForm(TeamsType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $filename = md5(uniqid()) . '.png';
            $imageFile->move($this->getParameter('Teams_image_directory'), $filename);
            $team->setImage($filename);
            $entityManager->persist($team);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Added succefully!'
            );

            return $this->redirectToRoute('teams_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('teams/new.html.twig', [
            'user' => $this->getUser(),

            'team' => $team,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/showTeams", name="teams_show")
     */
    public function show(): Response
    {   $team = new Teams();
        $repo =$this->getDoctrine()->getRepository(Teams::class);
        return $this->render('teams/TeamsBack.html.twig', [
            'user' => $this->getUser(),
            'team' => $team,
            'teamsList'=> $repo->findAll()
        ]);
    }

    /**
     * @Route("/admin/teams/{id}/edit", name="teams_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Teams $team, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TeamsBackType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            $this->addFlash(
                'info',
                'Updated succefully!'
            );

            return $this->redirectToRoute('teams_show');
        }

        return $this->render('teams/TeamUpdate.html.twig', [
            'user' => $this->getUser(),
            'team' => $team,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/teams/{id}/delete", name="teams_delete")
     */
    public function delete(Teams $team): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();
        $this->addFlash(
            'info',
            'Deleted succefully!'
        );

        $repo =$this->getDoctrine()->getRepository(Teams::class);
        return $this->redirectToRoute("teams_show");
       
}

    /**
     * @Route("/admin/stat", name="stat")
     */
    public function indexAction(){
        $repository = $this->getDoctrine()->getRepository(Teams::class);
        $team = $repository->findAll();
        $em = $this->getDoctrine()->getManager();

        $rd=0;
        $qu=0;



        foreach ($team as $team)
        {
            if (  $team->getVerified()==1):
                $rd+=1;
            else :

                $qu+=1;
            endif;

        }

        $pieChart= new PieChart();

        $pieChart->getData()->setArrayToDataTable(
            [['Etat', 'nombres'],
                ['Overte',     $rd],
                ['Fermé',      $qu]
            ]
        );
        $pieChart->getOptions()->setTitle('Top categories');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
//    dd($pieChart);
        return $this->render('teams/TeamsBack.html.twig', array('piechart' => $pieChart,'user'=>$this->getUser()));
    }

}