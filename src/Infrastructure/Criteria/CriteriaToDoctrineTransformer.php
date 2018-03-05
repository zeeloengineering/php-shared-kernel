<?php namespace StraTDeS\SharedKernel\Infrastructure\Criteria;

use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
use Doctrine\Common\Collections\Expr\Expression;
use StraTDeS\SharedKernel\Domain\Criteria\Criteria;
use StraTDeS\SharedKernel\Domain\Criteria\CriteriaItem;
use StraTDeS\SharedKernel\Domain\Criteria\CriteriaTransformerInterface;
use StraTDeS\SharedKernel\Domain\Criteria\Operator;

class CriteriaToDoctrineTransformer implements CriteriaTransformerInterface
{
    public function transform(Criteria $criteria): DoctrineCriteria
    {
        $doctrineCriteria = new DoctrineCriteria();

        $criteriaCurrentOperator = null;
        foreach($criteria->getCriteria() as $criteriaStep) {
            $criteriaStepCurrentOperator = null;
            foreach($criteriaStep as $criteriaConditionStep) {
                if($criteriaConditionStep instanceof CriteriaItem) {
                    /** @var CriteriaItem $criteriaConditionStep */
                    if($criteriaConditionStep->shouldApply()) {
                        if(!$criteriaStepCurrentOperator) {
                            $doctrineCriteria->where($this->getDoctrineCriteriaExpression($criteriaConditionStep));
                        } elseif($criteriaStepCurrentOperator == 'OR') {
                            $doctrineCriteria->orWhere($this->getDoctrineCriteriaExpression($criteriaConditionStep));
                        } else {
                            $doctrineCriteria->andWhere($this->getDoctrineCriteriaExpression($criteriaConditionStep));
                        }
                    }
                } else {
                    $criteriaStepCurrentOperator = $criteriaConditionStep;
                }
            }
        }

        $doctrineCriteria->orderBy($criteria->getOrderings());
        $doctrineCriteria->setFirstResult($criteria->getOffset());
        $doctrineCriteria->setMaxResults($criteria->getLimit());

        return $doctrineCriteria;
    }

    private function getDoctrineCriteriaExpression(CriteriaItem $criteriaItem): Expression
    {
        $expr = DoctrineCriteria::expr();

        if($criteriaItem->getOperator()->getOperator() == Operator::LIKE) {
            $expr = $expr->contains($criteriaItem->getFieldName(), $criteriaItem->getValue());
        } elseif($criteriaItem->getOperator()->getOperator() == Operator::EQUALS) {
            $expr = $expr->eq($criteriaItem->getFieldName(), $criteriaItem->getValue());
        } elseif($criteriaItem->getOperator()->getOperator() == Operator::NOT_EQUALS) {
            $expr = $expr->neq($criteriaItem->getFieldName(), $criteriaItem->getValue());
        }

        return $expr;
    }
}