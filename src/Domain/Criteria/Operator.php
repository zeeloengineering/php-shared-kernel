<?php namespace StraTDeS\SharedKernel\Domain\Criteria;

use StraTDeS\SharedKernel\Domain\GenericDomainException;

class Operator
{
    const EQUALS = '=';
    const NOT_EQUALS = '!=';
    const LIKE = 'LIKE';
    const NOT_LIKE = 'NOT_LIKE';
    const OR = 'OR';
    const AND = 'AND';

    const ALLOWED_OPERATORS = [
        self::EQUALS,
        self::NOT_EQUALS,
        self::LIKE,
        self::NOT_LIKE
    ];

    private $operator;

    /**
     * Operator constructor.
     * @param string $operator
     * @throws GenericDomainException
     */
    private function __construct(string $operator)
    {
        $this->checkOperatorIsValid($operator);

        $this->operator = $operator;
    }

    /**
     * @param string $operator
     * @return Operator
     * @throws GenericDomainException
     */
    public static function fromString(string $operator): Operator
    {
        return new self($operator);
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @throws GenericDomainException
     */
    private function checkOperatorIsValid(string $operator): void
    {
        if (!in_array($operator, self::ALLOWED_OPERATORS)) {
            throw new GenericDomainException("$operator is not an allowed operator");
        }
    }
}