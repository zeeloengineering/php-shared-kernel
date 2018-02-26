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
use StraTDeS\SharedKernel\Application\CQRS\ReadModel;
use StraTDeS\SharedKernel\Domain\EntityNotFoundException;
use StraTDeS\SharedKernel\Domain\ReadModelRepository;

abstract class DoctrineReadModelRepository implements ReadModelRepository
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
    public function get(string $id): ReadModel
    {
        /** @var ReadModel $readModel */
        $readModel = $this->entityManager->getRepository($this->getReadModelName())->find($id);

        if(!$readModel) {
            throw new EntityNotFoundException(
                'ReadModel ' . $this->getReadModelName() . ' with id ' . $id  . ' not found'
            );
        }

        return $readModel;
    }

    public function find(string $id): ?ReadModel
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

        return $this->entityManager->getRepository($this->getReadModelName())
            ->findBy([], $orderBy, $limit ?? 10, $offset ?? 0);
    }

    public function findByCriteria(array $criteria): array
    {
        return $this->entityManager->getRepository($this->getReadModelName())
            ->findBy($criteria);
    }

    /**
     * @param array $criteria
     * @return null|ReadModel|object
     */
    public function findOneByCriteria(array $criteria): ?ReadModel
    {
        return $this->entityManager->getRepository($this->getReadModelName())
            ->findOneBy($criteria);
    }

    /**
     * @param ReadModel $readModel
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(ReadModel $readModel): void
    {
        $this->entityManager->remove($readModel);
        $this->entityManager->flush();
    }

    public abstract function getReadModelName(): string;

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