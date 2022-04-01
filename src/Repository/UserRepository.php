<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */

    public function findByUsernameDiffId($username, $id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.id != :id')
            ->setParameter('username', $username)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    public function findUsersByDate()
//    {
//        return $this->createQueryBuilder('u')
//            ->select(['u.createdAt','count(u)'])
//            ->groupBy('u.createdAt')
//            ->getQuery()
//            ->getResult();
//    }

//    public function findOrCreateFromOauth(ResourceOwnerInterface $owner)
//    {
//        $user = $this->createQueryBuilder('u')
//            ->where('u.username = :id')
//            ->setParameter('id', $owner->toArray()['login'])
//            ->getQuery()
//            ->getOneOrNullResult();
//        if($user){
//            return $user;
//        }
//        $user = (new User())->setUsername($owner->toArray()['login'])->setEmail($owner->toArray()['email']);
//        $em = $this->getEntityManager();
//        $em->persist($user);
//        $em->flush();
//
//        return $user;
//    }
    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
