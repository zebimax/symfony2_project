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
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::TYPE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return [self::TRIVIAL, self::MINOR, self::MAJOR, self::BLOCKER];
    }
}
