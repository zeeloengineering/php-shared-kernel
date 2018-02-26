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
use StraTDeS\SharedKernel\Application\CQRS\ReadModel;
use StraTDeS\SharedKernel\Domain\PersistentReadModelRepository;

abstract class DoctrinePersistentReadModelRepository extends DoctrineReadModelRepository implements PersistentReadModelRepository
{
    /**
     * @inheritdoc
     */
    public function save(ReadModel $readModel): void
    {
        $this->entityManager->persist($readModel);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            throw new RepositoryException($e->getMessage(), $e->getCode());
        }
    }
}