<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class BaseRepository
{
    protected ?EntityRepository $repository = null;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected string $entityClass,
    ) {
    }

    protected function getRepository(): EntityRepository
    {
        return $this->repository ?? $this->repository = $this->entityManager->getRepository($this->entityClass);
    }

    public function find(string $id): ?object
    {
        try {
            return $this->getRepository()->find($id);
        } catch (\TypeError $ex) {
            return null;
        }
    }

    public function update($entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    public function remove($entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return $this->getQueryBuilderForAll()->getQuery()->execute();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder(substr($this->entityClass, 0, 1));
    }

    public function getQueryBuilderForDataGrid(): QueryBuilder
    {
        return $this->getQueryBuilderForAll();
    }
}
