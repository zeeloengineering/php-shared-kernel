<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

abstract class DomainEvent
{
    /** @var Id */
    private $id;

    /** @var Id */
    private $entityId;

    /** @var Id */
    private $creator;

    /** @var \DateTime */
    private $createdAt;

    public function __construct(
        Id $id,
        Id $entityId,
        Id $creator = null,
        \DateTime $createdAt = null
    )
    {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->creator = $creator;
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    final public function getId(): Id
    {
        return $this->id;
    }

    final public function getEntityId(): Id
    {
        return $this->entityId;
    }

    public function getCreator(): ?Id
    {
        return $this->creator;
    }

    final public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public abstract function getCode(): string;

    public abstract function getVersion(): int;

    public abstract function getData(): array;

    public abstract static function buildFromDataWithVersion(
        Id $id,
        Id $entityId,
        Id $creator = null,
        \DateTime $createdAt = null,
        array $data,
        int $eventVersion
    ): DomainEvent;
}