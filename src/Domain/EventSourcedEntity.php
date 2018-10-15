<?php namespace StraTDeS\SharedKernel\Domain;

use ReflectionException;

class EventSourcedEntity extends Entity
{
    /** @var bool */
    protected $disabled = false;

    /** @var bool */
    protected $isUpdatable = false;

    /** @var bool */
    protected $updated = false;

    /**
     * @param string $className
     * @return Entity
     * @throws ReflectionException
     */
    private static function generateEntityFromReflection(string $className): Entity
    {
        $reflectedEntity = new \ReflectionClass($className);
        /** @var Entity $entity */
        $entity = $reflectedEntity->newInstanceWithoutConstructor();

        $entity->isUpdatable = true;

        return $entity;
    }

    /**
     * @param DomainEvent $event
     * @throws EventApplierMethodNotDefinedException
     * @throws ReflectionException
     */
    final protected function recordAndApplyThat(DomainEvent $event): void
    {
        $this->recordThat($event);
        $this->apply($event);

        if ($this->isUpdatable) {
            $this->updated = true;
        }
    }

    /**
     * @param DomainEvent $event
     * @throws EventApplierMethodNotDefinedException
     * @throws ReflectionException
     */
    private function apply(DomainEvent $event): void
    {
        $methodName = self::getApplyMethodNameFromEventCode($event);
        $className = \get_class($this);

        if(!method_exists($this, $methodName)) {
            throw new EventApplierMethodNotDefinedException("$methodName is not defined for $className");
        }

        self::executeMethodFromReflection($this, $methodName, $event);
    }

    /**
     * @param string $className
     * @param EventStream $eventStream
     * @return Entity
     * @throws ReflectionException
     */
    public static function reconstituteFromEventStream(string $className, EventStream $eventStream): Entity
    {
        /** @var Entity $entity */
        $entity = self::generateEntityFromReflection($className);

        self::executeResetEventStreamFromReflection($entity);

        foreach($eventStream->getEvents() as $event) {
            self::executeApplyFromReflection($entity, $event);
        }

        return $entity;
    }

    private static function getApplyMethodNameFromEventCode($event): string
    {
        return 'apply' .
            str_replace('_', '', ucwords(strtolower($event->getCode()), '_'));
    }

    /**
     * @param $entity
     * @param $event
     * @throws ReflectionException
     */
    private static function executeApplyFromReflection($entity, $event): void
    {
        self::executeMethodFromReflection($entity, 'apply', $event);
    }

    /**
     * @param Entity $entity
     * @throws ReflectionException
     */
    private static function executeResetEventStreamFromReflection(Entity $entity): void
    {
        self::executeMethodFromReflection($entity, 'resetEventStream');
    }

    /**
     * @param $entity
     * @param $method
     * @param $param
     * @throws ReflectionException
     */
    private static function executeMethodFromReflection($entity, $method, $param = null): void
    {
        $method = new \ReflectionMethod($entity, $method);
        $method->setAccessible(true);
        $method->invoke($entity, $param);
        $method->setAccessible(false);
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }
}