<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Teams;
use App\Entity\User;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function admin(): Response
    {
        $user = $this->getUser();

        if(!$user){
            return $this->redirect('login');
        }
        return $this->render('app/admin.html.twig', [
            'user' => $this->getUser(),
            'piechart' => $this->stats(),
            'userStats' => $this->productStats()
        ]);
    }

    private function stats(){
        $repository = $this->getDoctrine()->getRepository(Teams::class);
        $teams = $repository->findAll();
        $em = $this->getDoctrine()->getManager();

        $rd=0;
        $qu=0;



        foreach ($teams as $team)
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
                ['Verified',     $rd],
                ['Not Verified',      $qu]
            ]
        );
        $pieChart->getOptions()->setBackgroundColor('transparent')->setTitle('POURCENTAGE OF VERIFIED TEAMS');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
//    dd($pieChart);
        return $pieChart;
    }

    private function productStats(){
        $chart = new \CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart();
        $categoriesProducts = $this->getDoctrine()->getRepository(Products::class)->findProductsByCategory();
        $array = [['Category', 'Products']];
        foreach($categoriesProducts as $product){
            array_push($array,$product);
        }

        $chart->getData()->setArrayToDataTable($array);

        $chart->getOptions()->getChart()
            ->setTitle('Products By Category');
        $chart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $chart->getOptions()->getTitleTextStyle()->setItalic(true);
        $chart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $chart->getOptions()->getTitleTextStyle()->setFontSize(20);
        $chart->getOptions()
            ->setBars('vertical')
            ->setHeight(400)
            ->setWidth(900)
            ->setBackgroundColor('transparent')
            ->setColors(['#1b9e77', '#d95f02', '#7570b3'])
            ->getVAxis()
            ->setFormat('decimal');
        return $chart;
    }
}
