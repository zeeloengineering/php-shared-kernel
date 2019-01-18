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

    /** @var string */
    private $encryptedKey;

    public function __construct(
        Id $entityId,
        Id $creator = null,
        \DateTime $createdAt = null,
        string $encryptedKey = null
    )
    {
        $this->id = UUIDV4::generate();
        $this->entityId = $entityId;
        $this->creator = $creator;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->encryptedKey = $encryptedKey;
    }

    /**
     * @param string $eventClassName
     * @param Id $id
     * @param Id $entityId
     * @param null|Id $creator
     * @param \DateTime|null $createdAt
     * @param array $data
     * @param int $dataVersion
     * @param string|null $encryptedKey
     * @return DomainEvent
     * @throws \ReflectionException
     */
    public static function newFromData(
        string $eventClassName,
        Id $id,
        Id $entityId,
        ?Id $creator,
        ?\DateTime $createdAt,
        array $data,
        int $dataVersion,
        string $encryptedKey = null
    ): DomainEvent
    {
        $reflectedEntity = new \ReflectionClass($eventClassName);

        /** @var DomainEvent $domainEvent*/
        $domainEvent = $reflectedEntity->newInstanceWithoutConstructor();

        $domainEvent->id = $id;
        $domainEvent->entityId = $entityId;
        $domainEvent->creator = $creator;
        $domainEvent->createdAt = $createdAt;
        $domainEvent->encryptedKey = $encryptedKey;
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
        return strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', current(array_reverse(explode("\\", \get_called_class())))));
    }

    final public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getVersion(): int
    {
        return static::EVENT_VERSION;
    }

    public function getEncryptedKey(): ?string
    {
        return $this->encryptedKey;
    }

    public function setEncryptedKey(string $encryptedKey = null): void
    {
        $this->encryptedKey = $encryptedKey;
    }

    abstract public function getData(): array;

    abstract public function setData(array $eventData, int $dataVersion): void;
}
