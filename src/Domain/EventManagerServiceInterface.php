<?php namespace StraTDeS\SharedKernel\Domain;

interface EventManagerServiceInterface
{
    public function handle(EventStream $eventStream): void;
}