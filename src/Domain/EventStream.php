<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

class EventStream
{
    /** @var DomainEvent[] */
    private $events = [];

    public function addEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return array|DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}