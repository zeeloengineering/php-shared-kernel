<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

interface PersistentRepository
{
    /**
     * @param Entity $entity
     * @throws RepositoryException
     */
    public function save(Entity $entity): void;
}