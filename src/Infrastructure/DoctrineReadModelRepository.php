<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Infrastructure;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use StraTDeS\SharedKernel\Application\CQRS\ReadModel;
use StraTDeS\SharedKernel\Domain\Criteria\Criteria;
use StraTDeS\SharedKernel\Domain\Criteria\CriteriaTransformerInterface;
use StraTDeS\SharedKernel\Domain\EntityNotFoundException;
use StraTDeS\SharedKernel\Domain\PageableCollection;
use StraTDeS\SharedKernel\Domain\ReadModelRepository;

abstract class DoctrineReadModelRepository implements ReadModelRepository
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var CriteriaTransformerInterface */
    protected $criteriaTransformer;

    public function __construct(EntityManager $entityManager, CriteriaTransformerInterface $criteriaTransformer)
    {
        $this->entityManager = $entityManager;
        $this->criteriaTransformer = $criteriaTransformer;
    }

    /**
     * @inheritdoc
     */
    public function get(string $id): ReadModel
    {
        /** @var ReadModel $readModel */
        $readModel = $this->entityManager->getRepository($this->getReadModelName())->find($id);

        if(!$readModel) {
            throw new EntityNotFoundException(
                'ReadModel ' . $this->getReadModelName() . ' with id ' . $id  . ' not found'
            );
        }

        return $readModel;
    }

    public function find(string $id): ?ReadModel
    {
        try {
            return $this->get($id);
        } catch(EntityNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param Criteria|null $criteria
     * @return PageableCollection
     */
    public function findByCriteria(Criteria $criteria = null): PageableCollection
    {
        /** @var \Doctrine\Common\Collections\Criteria $doctrineCriteria */
        $doctrineCriteria = $this->criteriaTransformer->transform($criteria);

        $result = $this->entityManager->getRepository($this->getReadModelName())
            ->matching($doctrineCriteria)->getValues();

        $doctrineCriteria->setMaxResults(null);
        $doctrineCriteria->setFirstResult(null);

        $count = $this->entityManager->getRepository($this->getReadModelName())
            ->matching($doctrineCriteria)->count();

        return new PageableCollection(
            $result,
            $count
        );
    }

    /**
     * @param Criteria $criteria
     * @return null|ReadModel|object
     */
    public function findOneByCriteria(Criteria $criteria = null): ?ReadModel
    {
        $criteria->limit(1);

        $results = $this->findByCriteria($criteria);

        if($results->getTotal() == 1) {
            return $results->getItems()[0];
        }

        return null;
    }

    public function findAllByCriteria(Criteria $criteria = null): PageableCollection
    {
        return $this->findByCriteria($criteria);
    }

    public function all(Criteria $criteria): PageableCollection
    {
        return $this->findByCriteria($criteria);
    }

    /**
     * @param ReadModel $readModel
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(ReadModel $readModel): void
    {
        $this->entityManager->remove($readModel);
        $this->entityManager->flush();
    }

    public abstract function getReadModelName(): string;
}