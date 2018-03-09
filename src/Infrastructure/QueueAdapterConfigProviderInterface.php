<?php namespace StraTDeS\SharedKernel\Infrastructure;

interface QueueAdapterConfigProviderInterface
{
    public function getHost(): string;

    public function getPort(): string;

    public function getUser(): string;

    public function getPassword(): string;
}