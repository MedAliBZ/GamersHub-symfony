<?php

namespace App\Repository;

use App\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blog[]    findAll()
 * @method Blog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }



     /**
      * @return Blog[] Returns an array of Blog objects
      */

    public function findByUsername($user)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Blog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     public function getAllPosts($post, $bw){
        $entityManager=$this->getEntityManager();
       $req=$entityManager
         ->createQuery("SELECT description FROM APP\Entity\Blog ")
          ;         
         $words =[];
         $rp =[];
         //dd($bw);
         foreach($bw as $word=>$m){
        //     //dd($m[0]->getWord());
        //     // $m[0]->getWord();
             array_push($words, $m);
             $r='';
             for($i=0; $i<strlen($m); $i++){
                 $r .= '*';
             }
            
             array_push($rp, $r);
            //  dd($rp);
         }
        // dd($rp);
         $postClean = str_replace($words, $rp, strtolower($post));
         //dd($postClean);
         return $postClean;
     }

}
