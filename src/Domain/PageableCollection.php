<?php namespace StraTDeS\SharedKernel\Domain;

class PageableCollection
{
    /** @var array */
    private $items;

    /** @var int */
    private $total;

    public function __construct(array $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}