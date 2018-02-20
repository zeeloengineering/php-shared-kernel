<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

abstract class Entity
{
    /** @var Id */
    protected $id;

    /** @var EventStream */
    private $eventStream;

    protected function __construct(Id $id)
    {
        $this->id = $id;
        $this->resetEventStream();
    }

    final public function recordThat(DomainEvent $event): void
    {
        $this->eventStream->addEvent($event);
    }

    final public function pullEventStream(): EventStream
    {
        $eventStream = $this->eventStream;

        $this->resetEventStream();

        return $eventStream;
    }

    private function resetEventStream(): void
    {
        $this->eventStream = new EventStream();
    }

    public function getId(): Id
    {
        return $this->id;
    }
}