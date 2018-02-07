<?php namespace StraTDeS\SharedKernel\Infrastructure\Doctrine\CustomType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use StraTDeS\SharedKernel\Domain\UUIDV4;

class DoctrineUUIDV4 extends Type
{
    /**
     * @param UUIDV4 $sqlExpr
     * @param AbstractPlatform $platform
     * @return string
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return $sqlExpr->getHumanReadableId();
    }

    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return UUIDV4::fromString($sqlExpr);
    }

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = 10;
        return $platform->getBinaryTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return DoctrineUUIDV4::class;
    }
}