<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

abstract class Id
{
    public abstract function getId();

    public abstract static function fromString(string $string): Id;

    public abstract function getHumanReadableId(): string;
}