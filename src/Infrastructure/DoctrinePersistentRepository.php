<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Infrastructure;

use Doctrine\ORM\OptimisticLockException;
use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\EventSourcedEntity;
use StraTDeS\SharedKernel\Domain\PersistentRepository;

abstract class DoctrinePersistentRepository extends DoctrineRepository implements PersistentRepository
{
    /**
     * @inheritdoc
     */
    public function save(Entity $entity): void
    {
        /** @var EventSourcedEntity $entity */
        $entity->setUpdated(false);
        $this->entityManager->persist($entity);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new RepositoryException($e->getMessage(), $e->getCode());
        }
    }
}