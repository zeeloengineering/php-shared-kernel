<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Tests\Unit\Domain;

use StraTDeS\SharedKernel\Domain\DomainEvent;
use StraTDeS\SharedKernel\Domain\Id;

class FakeDomainEvent extends DomainEvent
{
    public function __construct(Id $id, Id $entityId)
    {
        parent::__construct( $id,  $entityId);
    }
}