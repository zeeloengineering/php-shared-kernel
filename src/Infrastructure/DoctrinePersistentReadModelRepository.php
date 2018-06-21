<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Infrastructure;

use Doctrine\DBAL\DBALException;
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

    /**
     * @inheritdoc
     */
    public function clear(): void
    {
        try {
            $cmd = $this->entityManager->getClassMetadata($this->getReadModelName());
            $connection = $this->entityManager->getConnection();
            $dbPlatform = $connection->getDatabasePlatform();
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        } catch (DBALException $e) {
            throw new RepositoryException($e->getMessage(), $e->getCode());
        }
    }
}