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

interface ReadModelRepository
{
    /**
     * @param string $id
     * @return ReadModel
     * @throws EntityNotFoundException
     */
    public function get(string $id): ReadModel;

    public function find(string $id): ?ReadModel;

    public function all(int $offset, int $limit): array;

    public function findByCriteria(array $criteria): array;

    /**
     * @param array $criteria
     * @return null|ReadModel|object
     */
    public function findOneByCriteria(array $criteria): ?ReadModel;

    public function delete(ReadModel $readModel): void;
}