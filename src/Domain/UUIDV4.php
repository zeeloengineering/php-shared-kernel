<?php
/**
 * This file belongs to SharedKernel project.
 *
 * Author: Alex Hernández <info@alexhernandez.info>
 *
 * For license information, view LICENSE file in the root of the project.
 */

namespace StraTDeS\SharedKernel\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UUIDV4 extends GenerableId
{
    /** @var mixed */
    private $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function generate(): Id
    {
        return new self(Uuid::uuid4());
    }

    public static function fromString(string $string): Id
    {
        return new self(Uuid::fromString($string));
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getHumanReadableId(): string
    {
        return $this->id->toString();
    }
}