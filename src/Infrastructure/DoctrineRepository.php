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

    public function all(int $offset, int $limit): array
    {
        return $this->entityManager->getRepository($this->getEntityName())
            ->findBy([], null, $limit, $offset);
    }

    public abstract function getEntityName(): string;
}