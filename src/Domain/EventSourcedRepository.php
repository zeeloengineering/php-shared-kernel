<?php namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

interface EventSourcedRepository
{
    /**
     * @param Id $id
     * @return Entity
     * @throws EntityNotFoundException
     */
    public function get(Id $id): Entity;

    /**
     * @param Id $id
     * @return null|Entity
     */
    public function find(Id $id): ?Entity;

    /**
     * @param Entity $entity
     * @throws RepositoryException
     */
    public function save(Entity $entity): void;
}