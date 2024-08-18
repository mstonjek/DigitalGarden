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
}
