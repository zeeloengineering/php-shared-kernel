<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Application\CQRS\ReadModel;
use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

interface PersistentReadModelRepository
{
    /**
     * @param ReadModel $readModel
     * @throws RepositoryException
     */
    public function save(ReadModel $readModel): void;

    /**
     * @throws RepositoryException
     */
    public function clear(): void;
}