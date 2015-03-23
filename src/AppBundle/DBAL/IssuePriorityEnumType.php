<?php

namespace AppBundle\DBAL;

class IssuePriorityEnumType extends EnumType
{
    const TRIVIAL = 'trivial';
    const MINOR   = 'minor';
    const MAJOR   = 'major';
    const BLOCKER = 'blocker';

    protected $name = 'issue_priority_enum';
    protected $values = [self::TRIVIAL, self::MINOR, self::MAJOR, self::BLOCKER];
}
