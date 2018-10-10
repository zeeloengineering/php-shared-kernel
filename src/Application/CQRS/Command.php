<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Application\CQRS;

use StraTDeS\SharedKernel\Domain\EventSourcedEntity;

abstract class Command
{
    /**
     * @param EventSourcedEntity $entity
     * @return bool
     * @throws \ReflectionException
     */
    public function like(EventSourcedEntity $entity): bool
    {
        $commandReflection = new \ReflectionClass($this);
        $entityReflection = new \ReflectionClass($entity);

        $commandValues = array_map(function(\ReflectionProperty $property) {
            $property->setAccessible(true);
            return [
                'name' => $property->getName(),
                'value' => $property->getValue($this)
            ];
        }, $commandReflection->getProperties());

        $entityValues = array_map(function(\ReflectionProperty $property) use ($entity) {
            $property->setAccessible(true);
            return [
                'name' => $property->getName(),
                'value' =>
                    ($property->getName() === 'id') ?
                        $property->getValue($entity)->toHumandReadable() :
                        $property->getValue($entity)
            ];
        }, $entityReflection->getProperties());

        $changed = false;
        $i = 0;
        while ($changed === false && \count($commandValues) > $i) {

            $index = array_search($commandValues[$i]['name'], array_column($entityValues, 'name'), true);
            if (($index !== false) && ($commandValues[$i]['value'] !== $entityValues[$index]['value'])) {
                $changed = true;
            }
            $i++;
        }

        return $changed;
    }
}