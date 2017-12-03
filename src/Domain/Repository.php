<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

interface Repository
{
    public function get(Id $id): Entity;

    public function find(Id $id): ?Entity;

    public function all(): EntityCollection;
}