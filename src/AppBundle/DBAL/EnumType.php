<?php

namespace AppBundle\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(
            function ($val) {
                return sprintf('\'%s\'', $val);
            },
            $this->getValues()
        );

        return sprintf('ENUM(%s) COMMENT \'(DC2Type:%s)\'', implode(', ', $values), $this->getName());
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, (array) $this->getValues())) {
            throw new \InvalidArgumentException(sprintf('Invalid \'%s\' value', $this->getName()));
        }

        return $value;
    }

    /** @return array */
    abstract public function getValues();
}
