<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Tests\Unit\Domain;

use StraTDeS\SharedKernel\Domain\Entity;
use StraTDeS\SharedKernel\Domain\Id;

class FakeEntity extends Entity
{
    public function __construct(Id $id)
    {
        parent::__construct($id);
    }

    public function toArray(): array
    {
        return [];
    }
}