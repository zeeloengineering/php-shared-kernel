<?php namespace StraTDeS\SharedKernel\Domain\Criteria;

class Criteria
{
    /** @var array */
    private $criteria;

    /** @var int */
    private $currentCriteria;

    /** @var int */
    private $offset;

    /** @var int */
    private $limit;

    /** @var array */
    private $orderings;

    private function __construct()
    {
        $this->currentCriteria = 0;

        $this->criteria[$this->currentCriteria] = [];

        $this->offset = 0;
        $this->limit = null;
        $this->orderings = [];
    }

    public static function create(): Criteria
    {
        return new self();
    }

    public function condition(CriteriaItem $criteriaItem): Criteria
    {
        $this->criteria[$this->currentCriteria][] = $criteriaItem;

        return $this;
    }

    public function orCondition(CriteriaItem $criteriaItem): Criteria
    {
        $this->criteria[$this->currentCriteria][] = 'OR';
        $this->criteria[$this->currentCriteria][] = $criteriaItem;

        return $this;
    }

    public function andCondition(CriteriaItem $criteriaItem): Criteria
    {
        $this->criteria[$this->currentCriteria][] = 'AND';
        $this->criteria[$this->currentCriteria][] = $criteriaItem;

        return $this;
    }

    public function andCriteria(): Criteria
    {
        $this->currentCriteria++;
        $this->criteria[$this->currentCriteria] = 'AND';
        $this->currentCriteria++;
        $this->criteria[$this->currentCriteria] = [];

        return $this;
    }

    public function orCriteria(): Criteria
    {
        $this->currentCriteria++;
        $this->criteria[$this->currentCriteria] = 'OR';
        $this->currentCriteria++;
        $this->criteria[$this->currentCriteria] = [];

        return $this;
    }

    public function getCriteria(): array
    {
        return $this->criteria;
    }

    public function offset(int $offset): Criteria
    {
        $this->offset = $offset;

        return $this;
    }

    public function limit(int $limit): Criteria
    {
        $this->limit = $limit;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function orderBy(string $orderBy, string $orderDirection = null): Criteria
    {
        $this->orderings[$orderBy] = $orderDirection ?? 'ASC';

        return $this;
    }

    public function getOrderings(): array
    {
        return $this->orderings;
    }
}