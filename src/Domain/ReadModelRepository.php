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
use StraTDeS\SharedKernel\Domain\Criteria\Criteria;

interface ReadModelRepository
{
    /**
     * @param string $id
     * @return ReadModel
     * @throws EntityNotFoundException
     */
    public function get(string $id): ReadModel;

    public function find(string $id): ?ReadModel;

    public function all(Criteria $criteria): array;

    public function findByCriteria(Criteria $criteria = null): array;

    /**
     * @param Criteria $criteria
     * @return null|ReadModel|object
     */
    public function findOneByCriteria(Criteria $criteria = null): ?ReadModel;

    /**
     * @param Criteria $criteria
     * @return ReadModel[]
     */
    public function findAllByCriteria(Criteria $criteria = null): array;

    public function delete(ReadModel $readModel): void;
}