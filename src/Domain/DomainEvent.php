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
    const EVENT_VERSION = 1;

    /** @var Id */
    private $id;

    /** @var Id */
    private $entityId;

    /** @var Id */
    private $creator;

    /** @var \DateTime */
    private $createdAt;

    public function __construct(
        Id $entityId,
        Id $creator = null,
        \DateTime $createdAt = null
    )
    {
        $this->id = UUIDV4::generate();
        $this->entityId = $entityId;
        $this->creator = $creator;
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    public static function newFromData(
        string $eventClassName,
        Id $id,
        Id $entityId,
        ?Id $creator,
        ?\DateTime $createdAt,
        array $data,
        int $dataVersion
    ): DomainEvent
    {
        $reflectedEntity = new \ReflectionClass($eventClassName);

        /** @var DomainEvent $domainEvent*/
        $domainEvent = $reflectedEntity->newInstanceWithoutConstructor();

        $domainEvent->id = $id;
        $domainEvent->entityId = $entityId;
        $domainEvent->creator = $creator;
        $domainEvent->createdAt = $createdAt;
        $domainEvent->setData($data, $dataVersion);
        return $domainEvent;
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

    public function getCode(): string
    {
        return strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', current(array_reverse(explode("\\",get_called_class())))));
    }

    final public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getVersion(): int
    {
        return static::EVENT_VERSION;
    }

    public abstract function getData(): array;

    public abstract function setData(array $eventData, int $dataVersion);
}
