<?php namespace StraTDeS\SharedKernel\Application\CQRS;

abstract class ListableAndSearchableQuery extends ListableQuery
{
    private $search;

    public function __construct(
        int $page = null,
        int $perPage = null,
        string $orderBy = null,
        string $orderDirection = null,
        $search
    )
    {
        parent::__construct(
            $page,
            $perPage,
            $orderBy,
            $orderDirection
        );

        if (!is_null($search) && !is_array($search)) {
            $search = [$this->getDefaultSearchFieldName() => $search];
        }

        $this->search = $search;
    }

    public function getSearch(): array
    {
        return $this->search;
    }

    public abstract function getDefaultSearchFieldName(): string;

    public function getDefaultSearch(): array
    {
        return null;
    }
}