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

    /**
     * @param UUIDV4|null $eventStoreThreshold
     * @throws \Exception
     */
    public function cutUpTo(UUIDV4 $eventStoreThreshold = null)
    {
        if ($eventStoreThreshold !== null) {
            $newDomainEventArray = [];
            $found = false;
            foreach ($this->events as $domainEvent) {
                /** @var DomainEvent $domainEvent */
                $newDomainEventArray[] = $domainEvent;
                if ($domainEvent->getId()->getHumanReadableId() == $eventStoreThreshold->getHumanReadableId()) {
                    $found = true;
                    break;
                }
            }
            if(!$found) {
                throw new \Exception("You're trying to cut an event stream using an id that's not present on the event stream (".$eventStoreThreshold->getHumanReadableId().")");
            }
            $this->events = $newDomainEventArray;
        }

        return $this;
    }
}