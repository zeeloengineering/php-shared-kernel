<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use StraTDeS\SharedKernel\Domain\EventStream;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class EntityTest extends TestCase
{
    public function testRecordThatWithSomeEventsAndPullEventsReturnsProperValues()
    {
        // Arrange
        $domainEvent1 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $domainEvent2 = new FakeDomainEvent(UUIDV4::generate(), UUIDV4::generate());
        $entity = new FakeEntity(UUIDV4::generate());
        $expectedEventStream = new EventStream();
        $expectedEventStream->addEvent($domainEvent1);
        $expectedEventStream->addEvent($domainEvent2);

        // Act
        $entity->recordThat($domainEvent1);
        $entity->recordThat($domainEvent2);
        $eventStream = $entity->pullEventStream();
        $eventStreamAfterPull = $entity->pullEventStream();

        // Assert
        $this->assertEquals($expectedEventStream, $eventStream);
        $this->assertEquals([], $eventStreamAfterPull->getEvents());
    }
}
