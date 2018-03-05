<?php namespace StraTDeS\SharedKernel\Domain\Criteria;

use StraTDeS\SharedKernel\Domain\GenericDomainException;

class TextCriteriaItem extends CriteriaItem
{
    /**
     * @param string $fieldName
     * @param string $operator
     * @param string|null $value
     * @throws GenericDomainException
     */
    private function __construct(string $fieldName, string $operator, string $value = null)
    {
        $this->fieldName = $fieldName;
        $this->operator = Operator::fromString($operator);
        $this->value = $value;
    }

    /**
     * @param string $fieldName
     * @param string $operator
     * @param string|null $value
     * @return TextCriteriaItem
     * @throws GenericDomainException
     */
    public static function create(string $fieldName, string $operator, string $value = null) {
        return new self($fieldName, $operator, $value);
    }

    public function shouldApply(): bool
    {
        return $this->value && strlen($this->value) > 0;
    }
}