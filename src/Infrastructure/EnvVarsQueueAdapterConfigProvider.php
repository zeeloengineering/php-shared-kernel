<?php namespace StraTDeS\SharedKernel\Infrastructure;

class EnvVarsQueueAdapterConfigProvider implements QueueAdapterConfigProviderInterface
{
    public function getHost(): string
    {
        return getenv('RABBITMQ_HOST');
    }

    public function getPort(): string
    {
        return getenv('RABBITMQ_PORT');
    }

    public function getUser(): string
    {
        return getenv('RABBITMQ_USER');
    }

    public function getPassword(): string
    {
        return getenv('RABBITMQ_PASSWORD');
    }
}