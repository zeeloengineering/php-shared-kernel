<?php namespace StraTDeS\SharedKernel\Application\CQRS;

class PageableQueryResult extends QueryResult
{
    /** @var int */
    private $page;

    /** @var int */
    private $perPage;

    /** @var int */
    private $total;

    public function __construct(int $page, int $perPage, int $total)
    {
        $this->page = $page;
        $this->perPage = $perPage;
        $this->total = $total;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}