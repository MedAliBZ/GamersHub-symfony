<?php

namespace App\Controller;

use App\Entity\Matchs;
use App\Form\MatchsType;
use App\Repository\MatchsRepository;
use App\Repository\TeamsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/matchs")
 */
class MatchsController extends AbstractController
{
    /**
     * @Route("/", name="matchs_index")
     */
    public function index(Request $request,MatchsRepository $matchsRepository,PaginatorInterface $paginator): Response
    {

        $repo =$this->getDoctrine()->getRepository(Matchs::class)->findBy([],['match_date' => 'desc']);
        $match = $paginator->paginate(
            $repo, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3 // Nombre de résultats par page
        );
        return $this->render('matchs/Match.html.twig', [
            'user' => $this->getUser(),

            'MatchsList'=> $match
        ]);
        
    }


    /**
     * @Route("/new", name="matchs_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $match = new Matchs();
        $form = $this->createForm(MatchsType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($match);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Added succefully!'
            );

            return $this->redirectToRoute('matchs_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matchs/new.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Admin/showMatchs", name="matchs_show", methods={"GET"})
     */
    public function show(): Response
    {
        $match = new Matchs();
        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        
        
        return $this->render('matchs/MatchsBack.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'MatchsList'=> $repo->findAll()
            
        ]);
    }

    /**
     * @Route("/admin/matchs/{id}/edit", name="matchs_edit")
     */
    public function edit(Request $request, Matchs $match, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MatchsType::class, $match);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Updated succefully!'
            );

            return $this->redirectToRoute('matchs_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('matchs/MatchUpdate.html.twig', [
            'user' => $this->getUser(),
            'match' => $match,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/matchs/{id}/delete", name="matchs_delete")
     */
    public function delete(Matchs $match): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($match);
        $em->flush();
        $this->addFlash(
            'info',
            'Deleted succefully!'
        );

        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        return $this->redirectToRoute("matchs_show");
       

}
    /**
     * @Route("admin/matchs/pdf", name="matchs_pdf")
     */
    public function impressionPDF()
    {
        $match = new Matchs();
        $repo =$this->getDoctrine()->getRepository(Matchs::class);
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('matchs/pdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'user' => $this->getUser(),
            'MatchsList'=> $repo->findAll(),
            'match' => $match,
        ]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("myMatchs.pdf", [
            "Attachment" => true
        ]);
    }
}
