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
use StraTDeS\SharedKernel\Domain\UUIDV4;

class DomainEventTest extends TestCase
{
    public function testDomainEventReturnsProperValues()
    {
        // Arrange
        $id = UUIDV4::generate();
        $entityId = UUIDV4::generate();

        $domainEvent = new FakeDomainEvent($id, $entityId);

        // Act
        $responseId = $domainEvent->getId();
        $responseEntityId = $domainEvent->getEntityId();
        $createdAt = $domainEvent->getCreatedAt();

        // Assert
        $this->assertEquals($id, $responseId);
        $this->assertEquals($entityId, $responseEntityId);
        $this->assertInstanceOf(\DateTime::class, $createdAt);
    }
}
