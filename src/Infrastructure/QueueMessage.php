<?php namespace StraTDeS\SharedKernel\Infrastructure;

class QueueMessage
{
    /** @var string */
    private $id;

    /** @var string */
    private $body;

    public function __construct(string $id, string $body)
    {
        $this->id = $id;
        $this->body = $body;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}