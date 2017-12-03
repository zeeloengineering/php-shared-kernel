<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Tests\Unit\Domain;

use StraTDeS\SharedKernel\Domain\EntityCollection;
use PHPUnit\Framework\TestCase;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class EntityCollectionTest extends TestCase
{
    public function testEntityCollectionReturnsProperValues()
    {
        // Arrange
        $fakeEntityArray = [
            new FakeEntity(UUIDV4::generate()),
            new FakeEntity(UUIDV4::generate()),
            new FakeEntity(UUIDV4::generate()),
            new FakeEntity(UUIDV4::generate())
        ];
        $fakeEntityCollection = new FakeEntityCollection($fakeEntityArray);

        // Act
        $responseFakeEntityArray = $fakeEntityCollection->getEntities();

        // Assert
        $this->assertEquals($fakeEntityArray, $responseFakeEntityArray);
    }
}
