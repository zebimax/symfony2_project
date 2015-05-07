<?php

namespace AppBundle\DBAL;

class IssuePriorityEnumType extends EnumType
{
    const TRIVIAL = 'trivial';
    const MINOR   = 'minor';
    const MAJOR   = 'major';
    const BLOCKER = 'blocker';

    const TYPE_NAME = 'issue_priority_enum';

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::TYPE_NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getValues()
    {
        return [self::TRIVIAL, self::MINOR, self::MAJOR, self::BLOCKER];
    }
}
