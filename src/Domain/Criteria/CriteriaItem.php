<?php namespace StraTDeS\SharedKernel\Domain\Criteria;

abstract class CriteriaItem
{
    protected $fieldName;
    protected $operator;
    protected $value;

    public abstract function shouldApply(): bool;

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getOperator(): Operator
    {
        return $this->operator;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function andCriteria(CriteriaItem $criteria): CriteriaItem
    {

    }
}