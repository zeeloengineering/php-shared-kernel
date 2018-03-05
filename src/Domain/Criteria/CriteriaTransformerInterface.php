<?php namespace StraTDeS\SharedKernel\Domain\Criteria;

interface CriteriaTransformerInterface
{
    public function transform(Criteria $criteria);
}