<?php namespace StraTDeS\SharedKernel\Application\CQRS;

use StraTDeS\SharedKernel\Domain\UUIDV4;

abstract class ReadModel
{
    /** @var string */
    protected $id;

    /** @var \DateTime */
    private $createdAt;

    /** @var \DateTime */
    private $updatedAt;

    public function __construct(\DateTime $createdAt = null, \DateTime $updatedAt = null)
    {
        $this->id = UUIDV4::generate();
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

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}