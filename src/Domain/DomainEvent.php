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

    /** @var \DateTime */
    private $createdAt;

    protected function __construct(Id $id, Id $entityId)
    {
        $this->id = $id;
        $this->entityId = $entityId;
        $this->createdAt = new \DateTime();
    }

    final public function getId(): Id
    {
        return $this->id;
    }

    final public function getEntityId(): Id
    {
        return $this->entityId;
    }

    final public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}