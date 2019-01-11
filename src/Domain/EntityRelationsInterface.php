<?php namespace StraTDeS\SharedKernel\Domain;

interface EntityRelationsInterface
{
    /**
     * @param int $version
     */
    public static function setMaxVersion(int $version): void;

    /**
     * @param Entity $entity
     * @param Id $eventStoreId
     * @param bool $disableFK
     */
    public function save(Entity $entity, Id $eventStoreId, $disableFK = false): void;

    /**
     * @param Id $id
     * @param $entityName
     * @return RelationsQueryBuilder
     */
    public function findAll(Id $id, $entityName): RelationsQueryBuilder;

    /**
     * @param RelationsQueryBuilder $relationsQueryBuilder
     * @return array
     */
    public function runQuery(RelationsQueryBuilder $relationsQueryBuilder, Id $eventStoreThreshold=null): array;

    /**
     * @param Id|null $id
     * @return int
     */
    public function getLastVersion(Id $id=null): int;
}