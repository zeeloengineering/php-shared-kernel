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
            $this->mapKeys($orderBy),
            $orderDirection
        );

        $search = $this->convertStringToArray($search);
        $search = $this->mapKeys($search);

        $this->search = $search;
    }

    public function getSearch(): ?array
    {
        return $this->search;
    }

    public abstract function getDefaultSearchFieldName(): string;

    public abstract function getFieldsMapping(): array;

    public function getDefaultSearch(): array
    {
        return null;
    }

    private function mapKeys($search = null)
    {
        if(!is_null($search)) {
            foreach ($search as $key => $value) {
                if (isset($this->getFieldsMapping()[$key])) {
                    $newKey = $this->getFieldsMapping()[$key];

                    $search[$newKey] = $value;
                    unset($search[$key]);
                }
            }
        }

        return $search;
    }

    private function convertStringToArray($search): ?array
    {
        if (!is_null($search) && !is_array($search)) {
            $search = [$this->getDefaultSearchFieldName() => $search];
        }

        return $search;
    }
}