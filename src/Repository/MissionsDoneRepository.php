<?php

namespace App\Repository;

use App\Entity\MissionsDone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MissionsDone|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionsDone|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionsDone[]    findAll()
 * @method MissionsDone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionsDoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionsDone::class);
    }

     /**
      * @return MissionsDone[] Returns an array of MissionsDone objects
      */

    public function findDoneMissions($username)
    {
        return $this->createQueryBuilder('m')
            ->join('m.user', 'u')
            ->where('u.username = :username')
            ->andWhere('m.isClaimed = true')
            ->setParameter('username', $username)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?MissionsDone
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
