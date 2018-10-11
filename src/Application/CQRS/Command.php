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
use StraTDeS\SharedKernel\Domain\Id;

abstract class Command
{
    /**
     * @param EventSourcedEntity $entity
     * @return bool
     * @throws \ReflectionException
     */
    public function like(EventSourcedEntity $entity): bool
    {
        $commandValues = $this->getCommandValues();
        $entityValues = $this->getEntityValues($entity);

        return $this->areCommandAndEntityDifferent($commandValues, $entityValues);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function getCommandValues(): array
    {
        $commandReflection = new \ReflectionClass($this);

        $commandValues = array_map(function (\ReflectionProperty $property) {
            $property->setAccessible(true);
            $result = [
                'name' => $property->getName(),
                'value' => $property->getValue($this)
            ];
            $property->setAccessible(false);
            return $result;
        }, $commandReflection->getProperties());

        return $commandValues;
    }

    /**
     * @param EventSourcedEntity $entity
     * @return array
     * @throws \ReflectionException
     */
    private function getEntityValues(EventSourcedEntity $entity): array
    {
        $entityReflection = new \ReflectionClass($entity);

        $entityValues = array_map(function (\ReflectionProperty $property) use ($entity) {
            $property->setAccessible(true);
            if ($property->getName() === 'id') {
                $value = $property->getValue($entity)->toHumanReadable();
            } else if (\is_array($property->getValue($entity))) {
                if ($property->getValue($entity) === null || empty($property->getValue($entity))) {
                    $value = $property->getValue($entity);
                } else {
                    $value = array_map(function (Id $id) {
                        return $id->getHumanReadableId();
                    }, $property->getValue($entity));
                }
            } else {
                $value = $property->getValue($entity);
            }
            $result = [
                'name' => $property->getName(),
                'value' => $value
            ];
            $property->setAccessible(false);
            return $result;
        }, $entityReflection->getProperties());
        return $entityValues;
    }

    /**
     * @param array $commandValues
     * @param array $entityValues
     * @return bool
     */
    private function areCommandAndEntityDifferent(array $commandValues, array $entityValues): bool
    {
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