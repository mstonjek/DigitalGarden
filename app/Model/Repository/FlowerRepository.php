<?php

namespace App\Repository;

use App\Model\Entity\Flower;
use App\Model\Entity\User;
use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;

class FlowerRepository extends BaseRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Flower::class);
    }

    public function findFlowerByUser(User $user): ?Flower
    {
        return $this->getRepository()->findOneBy(['user' => $user]);
    }

    public function getFlowerCount(): int
    {
        return $this->getRepository()->count([]);
    }
}
