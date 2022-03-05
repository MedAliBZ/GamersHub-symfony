<?php

namespace App\Repository;

use App\Entity\MockCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MockCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method MockCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method MockCommande[]    findAll()
 * @method MockCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MockCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MockCommande::class);
    }

    // /**
    //  * @return MockCommande[] Returns an array of MockCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MockCommande
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
