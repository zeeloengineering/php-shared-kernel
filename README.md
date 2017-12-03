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
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getSurname(): string
    {
        return $this->surname;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->getId()->getHumanReadableId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname()
        ];
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
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getSurname(): string
    {
        return $this->surname;
    }
    
    public function toArray(): array
    {
        return [
            'id' => $this->getId()->getHumanReadableId(),
            'name' => $this->getName(),
            'surname' => $this->getSurname()
        ];
    }
}

$person = new Person(
    UUIDV4::generate(),
    'Alex',
    'Hernández'
);

$eventStream = $person->pullEventStream();
```

Note you must implements toArray method. This method is very useful when you data transform entities or event to
to serialize it.

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

## UseCase

A very simple way to access Domain and Infrastructure layers from your console commands or controllers is through an
Application UseCase. A UseCase receives a Request and may return (or null) a Response. Let's see an example:

```php
<?php

use StraTDeS\SharedKernel\Application\UseCase\UseCase;
use StraTDeS\SharedKernel\Application\UseCase\Request;
use StraTDeS\SharedKernel\Application\UseCase\Response;
use StraTDeS\SharedKernel\Domain\Id;
use StraTDeS\SharedKernel\Application\DataTransformer;

class PersonCollectionToArrayDataTransformer implements DataTransformer
{
    /**
     * @param mixed|PersonCollection $data
     * @return array
     */
    public function transform(mixed $data): mixed
    {
        $persons = [];
        
        foreach($data as $person) {
            $persons[] = $person->toArray();
        }
        
        return $persons;
    }
}

class GetUserByIdRequest extends Request
{
    private $id;
    
    public function __construct(Id $id) 
    {
        $this->id = $id;
    }
    
    public function getId(): Id
    {
        return $this->id;
    }
}

class GetUserByIdResponse extends Response
{
    private $persons;
    
    public function __construct(mixed $persons) 
    {
        $this->persons = $persons;
    }
    
    public function getPersons(): mixed
    {
        return $this->persons;
    }
}

class GetUserByIdUseCase extends UseCase
{
    private $dataTransformer;
    
    public function __construct(DataTransformer $dataTransformer) 
    {
        $this->dataTransformer = $dataTransformer;
    }
    
    public function execute(Request $getUserByIdRequest): Response
    {
        $userCollection = //my repository query returns a PersonCollection
        
        return new GetUserByIdResponse($this->dataTransformer->transform($userCollection));
    }
}
```

Probably you have noted the DataTransformer object. It provides functionality to transform any object into any other
in the application layer. This means you can use the same UseCase to retrieve information in different formats just
injecting a different data transformer. This is basic to not return domain objects to the infrastructure layer.

I recommend defining your use cases with different names based in the data transformer you inject. For example:

- get_user_by_id_use_case_data_transformed_to_array
- get_user_by_id_use_case_data_transformed_to_json

This can be easily done if you use a decent dependency injector. 

## CQRS

CQRS stands for Command Query Responsibility Segregation and basically means that a method should either return
a value of modify it's context, never both things at the same time.

I have provided with some useful interfaces to work both with Commands (to change context) and Queries (to retrieve
information). This is used to be done through command and query buses. A bus accepts some kind of request (a Command
or a Query) and processes it through a handler (a CommandHandler or a QueryHandler). Middlewares can be added along
the process.

So in the namespace StraTDeS\SharedKernel\Application\CQRS you have abstract classes for both Command and Query, and
interfaces for both CommandHandler and QueryHandler.