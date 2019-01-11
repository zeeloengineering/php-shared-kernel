<?php namespace StraTDeS\SharedKernel\Domain;

class RelationsQueryBuilder
{
    /** @var array $heap */
    protected $heap = [];

    /** @var EntityRelationsInterface $entityRelations */
    protected $entityRelations;

    /**
     * RelationsQueryBuilder constructor.
     * @param string $id
     * @param array $className
     * @param EntityRelationsInterface $entityRelations
     */
    public function __construct(string $id, array $className, EntityRelationsInterface $entityRelations)
    {
        $this->heap[] = [$id, $className];
        $this->entityRelations = $entityRelations;
    }

    /**
     * @param string|array $className
     * @return $this
     * @throws \Exception
     */
    public function findAll($className)
    {
        if (\is_string($className)) {
            $className = [$className];
        } elseif(!\is_array($className)) {
            throw new \Exception('FindAll: First argument must be an array or string');
        }
        $this->heap[] = [$className];

        return $this;
    }

    /**
     * @param EventSourcedRepositoryInterface|null $repository
     * @return array
     */
    public function get(EventSourcedRepositoryInterface $repository = null): array
    {
        $ids = $this->entityRelations->runQuery($this);

        if ($repository === null) {
            return $ids;
        }

        return array_map(function($element) use ($repository) {
            return $repository->find(UUIDV4::fromString($element));
        }, $ids);
    }

    /**
     * @return array
     */
    public function getHeap(): array
    {
        return $this->heap;
    }
}