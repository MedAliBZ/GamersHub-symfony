<?php

namespace App\Repository;

use App\Entity\WishList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WishList|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishList|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishList[]    findAll()
 * @method WishList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WishList::class);
    }

    // /**
    //  * @return WishList[] Returns an array of WishList objects
    //  */
   
    public function findByUser($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.user = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
   

   
    public function findByProduct($value): ?WishList
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.product = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
