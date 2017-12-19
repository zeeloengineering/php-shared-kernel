<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

abstract class EntityCollection
{
    /** @var Entity[] */
    private $entities;

    public function __construct(array $entities)
    {
        $this->entities = $entities;
    }

    public function getEntities(): array
    {
        return $this->entities;
    }

    public function toArray(): array
    {
        $entities = [];

        foreach($this->entities as $entity) {
            $entities[] = $entity->toArray();
        }

        return $entities;
    }
}