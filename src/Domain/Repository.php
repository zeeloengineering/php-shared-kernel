<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Domain\Criteria\Criteria;

interface Repository
{
    /**
     * @param Id $id
     * @return Entity
     * @throws EntityNotFoundException
     */
    public function get(Id $id): Entity;

    public function find(Id $id): ?Entity;

    public function all(int $offset, int $limit): array;

    public function findByCriteria(Criteria $criteria = null): array;

    /**
     * @param Criteria $criteria
     * @return null|Entity|object
     */
    public function findOneByCriteria(Criteria $criteria = null): ?Entity;

    public function delete(Entity $entity): void;
}