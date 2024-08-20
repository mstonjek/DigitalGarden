<?php

namespace App\Model\Repository;


use App\Model\Entity\User;
use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, User::class);
    }

    public function findByGithubId(int $githubId): ?User
    {
        return $this->getRepository()->findOneBy(['githubId' => $githubId]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->getRepository()->findOneBy(['username' => $username]);
    }

    public function search(string $query = "", int $limit = 50) {
        $queryBuilder = $this->getRepository()->createQueryBuilder('l');

        $orX = $queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('l.username', ':query'),
        );

        $queryBuilder->setMaxResults($limit);

        return $queryBuilder->where($orX)
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->execute();
    }


}
