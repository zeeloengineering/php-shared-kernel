<?php namespace StraTDeS\SharedKernel\Application\CQRS;

abstract class ListableQuery extends Query
{
    /** @var int */
    private $page;

    /** @var int */
    private $perPage;

    /** @var string */
    private $orderBy;

    /** @var string */
    private $orderDirection;

    public function __construct(
        int $page = null,
        int $perPage = null,
        string $orderBy = null,
        string $orderDirection = null
    )
    {
        $this->page = $page ?? $this->getDefaultPage();
        $this->perPage = $perPage ?? $this->getDefaultPerPage();
        $this->orderBy = $orderBy ?? $this->getDefaultOrderBy();
        $this->orderDirection = $orderDirection ?? $this->getDefaultOrderDirection();
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    public function getDefaultPage(): int
    {
        return 1;
    }

    public function getDefaultPerPage(): int
    {
        return 10;
    }

    public function getDefaultOrderBy(): ?string
    {
        return null;
    }

    public function getDefaultOrderDirection(): ?string
    {
        return null;
    }
}