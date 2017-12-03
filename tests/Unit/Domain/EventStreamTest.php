<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Tests\Unit\Domain;

use StraTDeS\SharedKernel\Domain\EventStream;
use PHPUnit\Framework\TestCase;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class EventStreamTest extends TestCase
{
    public function testEventStreamReturnsProperValues()
    {
        // Arrange
        $domainEvent1 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $domainEvent2 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $domainEvent3 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $domainEvent4 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $domainEvent5 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());

        $expectedDomainEventsArray = [
            $domainEvent1,
            $domainEvent2,
            $domainEvent3,
            $domainEvent4,
            $domainEvent5
        ];

        $eventStream = new EventStream();
        $eventStream->addEvent($domainEvent1);
        $eventStream->addEvent($domainEvent2);
        $eventStream->addEvent($domainEvent3);
        $eventStream->addEvent($domainEvent4);
        $eventStream->addEvent($domainEvent5);

        // Act
        $responseDomainEventArray = $eventStream->getEvents();

        // Assert
        $this->assertEquals($expectedDomainEventsArray, $responseDomainEventArray);
    }
}
