<?php namespace StraTDeS\SharedKernel\Domain;

use StraTDeS\SharedKernel\Infrastructure\RepositoryException;

class EventManagerService implements EventManagerServiceInterface
{
    /** @var EventStoreInterface */
    private $eventStore;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventStoreInterface $eventStore, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventStore = $eventStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param EventStream $eventStream
     * @throws RepositoryException
     */
    public function handle(EventStream $eventStream): void
    {
        foreach($eventStream->getEvents() as $domainEvent) {
            $this->eventStore->save($domainEvent);
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}