<?php namespace StraTDeS\SharedKernel\Domain;

interface EventDispatcherInterface
{
    public function dispatch(DomainEvent $domainEvent): void;
    public function getListenersGroupedByEventCode(iterable $listeners): array;
}