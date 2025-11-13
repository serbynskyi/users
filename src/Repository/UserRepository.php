<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

        public function findUsers(): array
        {
            return $this->createQueryBuilder('u')
                ->select('u.id, u.login, u.phone, u.roles')
                ->getQuery()
                ->getArrayResult();
        }

        public function findOneById($id): array
        {
            return $this->createQueryBuilder('u')
                ->select('u.id, u.login, u.phone, u.roles')
                ->andWhere('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getArrayResult();
        }
}
