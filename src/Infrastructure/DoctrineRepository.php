<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\EntityNotFoundException;
use StraTDeS\SharedKernel\Domain\Id;
use StraTDeS\SharedKernel\Domain\Repository;

abstract class DoctrineRepository implements Repository
{
    /** @var EntityManager */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritdoc
     */
    public function get(Id $id): Entity
    {
        /** @var Entity $entity */
        $entity = $this->entityManager->getRepository($this->getEntityName())->find($id);

        if(!$entity) {
            throw new EntityNotFoundException(
                'Entity ' . $this->getEntityName() . ' with id ' . $id->getHumanReadableId()  . ' not found'
            );
        }

        return $entity;
    }

    public function find(Id $id): ?Entity
    {
        try {
            return $this->get($id);
        } catch(EntityNotFoundException $e) {
            return null;
        }
    }

    public function all(int $offset = null, int $limit = null, $orderColumn = null, $orderDirection = null): array
    {
        $orderBy = $this->generateOrderByArray($orderColumn, $orderDirection);

        return $this->entityManager->getRepository($this->getEntityName())
            ->findBy([], $orderBy, $limit ?? 10, $offset ?? 0);
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->findBy($criteria);
    }

    public function findOneByCriteria(array $criteria): ?Entity
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->findOneBy($criteria);
    }

    /**
     * @param Entity $entity
     * @throws OptimisticLockException
     */
    public function delete(Entity $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public abstract function getEntityName(): string;

    /**
     * @param $orderColumn
     * @param $orderDirection
     * @return array|null
     */
    private function generateOrderByArray($orderColumn, $orderDirection)
    {
        $orderBy = null;
        if ($orderColumn && $orderDirection) {
            $orderBy = [$orderColumn => $orderDirection];
        }
        return $orderBy;
    }
}