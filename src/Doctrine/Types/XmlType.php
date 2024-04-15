<?php
// src/Doctrine/Types/XmlType.php
namespace App\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Custom XML type for Doctrine mapping.
 */
class XmlType extends Type
{
    public const XML = 'xml';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value !== null) ? new \SimpleXMLElement($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return ($value instanceof \SimpleXMLElement) ? $value->asXML() : null;
    }

    public function getName()
    {
        return self::XML;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
