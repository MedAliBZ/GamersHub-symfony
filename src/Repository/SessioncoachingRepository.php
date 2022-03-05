<?php

namespace App\Repository;

use App\Entity\Sessioncoaching;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sessioncoaching|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sessioncoaching|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sessioncoaching[]    findAll()
 * @method Sessioncoaching[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessioncoachingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sessioncoaching::class);
    }

    // /**
    //  * @return Sessioncoaching[] Returns an array of Sessioncoaching objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sessioncoaching
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
