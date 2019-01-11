<?php namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

interface EventStoreInterface
{
    /**
     * @param DomainEvent $domainEvent
     * @throws RepositoryException
     */
    public function save(DomainEvent $domainEvent): void;

    public function getByEntityId(Id $id): EventStream;

    public function getByEventCodes(array $eventCodes): EventStream;

    public function getAllEvents(): EventStream;

    public function entityIdExists(Id $id): bool;

    public function getEntityIdList(): array;
}