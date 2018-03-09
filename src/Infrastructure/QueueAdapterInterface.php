<?php namespace StraTDeS\SharedKernel\Infrastructure;

interface QueueAdapterInterface
{
    public function get (string $queueName): QueueMessage;

    public function consume (string $queueName, callable $consumer, bool $ack = true): void;

    public function send (string $message, string $queueName): void;

    public function ack (QueueMessage $queueMessage): void;

    public function close(): void;
}