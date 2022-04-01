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
            ->getResult();
    }


    /*
        public function findOneByUserAndMission($user,$mission): ?MissionsDone
        {
            return $this->createQueryBuilder('m')
                ->where('m.mission_id = :mission')
                ->andWhere('m.user_id = :user')
                ->setParameter('mission', $mission)
                ->setParameter('user', $user)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }
    */
}
