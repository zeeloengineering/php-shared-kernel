# Shared kernel

Simple shared kernel for DDD applications, with many scaffolding code for Hexagonal architecture, event sourcing, etc.

## Entities

You can create a new entity just inheriting from Entity class:

```php
<?php

use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\Id;

class Person extends Entity
{
    private $name;
    
    private $surname;

    public function __construct(Id $id, string $name, string $surname)
    {
        parent::__construct($id);
        
        $this->name = $name;
        $this->surname = $surname;
    }
}
```

Implementing constructor is mandatory as Id is controlled from base class.

As far as you have inherited from Entity, you have an EventStreamAvailable, so you can record events and retrieve the
EventStream:

```php
<?php

use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\Id;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class Person extends Entity
{
    private $name;
    
    private $surname;

    public function __construct(Id $id, string $name, string $surname)
    {
        parent::__construct($id);
        
        $this->name = $name;
        $this->surname = $surname;
        
        $this->recordThat(
            new PersonCreated(
                UUIDV4::generate(),
                $this->getId(),
                $name,
                $surname
            )
        );
    }
}

$person = new Person(
    UUIDV4::generate(),
    'Alex',
    'Hernández'
);

$eventStream = $person->pullEventStream();
```

## Entity collections

As you are eventually going to return arrays of entities from your repositories, I have prepared a simple
entity collection for you to inherit:

```php
<?php

use StraTDeS\SharedKernel\Domain\EntityCollection;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class PersonCollection extends EntityCollection
{
    
}

$personCollection = new PersonCollection([
    new Person(
        UUIDV4::generate(),
        'Alex',
        'Hernández'
    ),
    new Person(
        UUIDV4::generate(),
        'John',
        'Smith'
    )
]);

$entities = $personCollection->getEntities();

```

## DomainEvents

You have a base class DomainEvent to inherit from when you want to create an Event. As simple as that:

```php
<?php

use StraTDeS\SharedKernel\Domain\DomainEvent;
use StraTDeS\SharedKernel\Domain\Id;

class PersonCreated extends DomainEvent
{
    private $name;
    
    private $surname;
    
    public function __construct(Id $id, Id $entityId, string $name, string $surname)
    {
        parent::__construct($id, $entityId);
        
        $this->name = $name;
        $this->surname = $surname;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getSurname(): string
    {
        return $this->surname;
    }
}
```