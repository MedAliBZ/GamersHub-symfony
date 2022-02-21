<?php

namespace App\Repository;

use App\Entity\Tournaments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tournaments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournaments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournaments[]    findAll()
 * @method Tournaments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournamentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournaments::class);
    }

    // /**
    //  * @return Tournaments[] Returns an array of Tournaments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tournaments
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
