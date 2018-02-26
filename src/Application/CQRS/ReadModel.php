<?php namespace StraTDeS\SharedKernel\Application\CQRS;

abstract class ReadModel
{
    /** @var string */
    protected $id;

    /** @var \DateTime */
    private $createdAt;

    /** @var \DateTime */
    private $updatedAt;

    public function __construct(string $id, \DateTime $createdAt = null, \DateTime $updatedAt = null)
    {
        $this->id = $id;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt ?? new \DateTime();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}