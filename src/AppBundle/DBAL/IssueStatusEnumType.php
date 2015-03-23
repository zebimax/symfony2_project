<?php

namespace AppBundle\DBAL;

class IssueStatusEnumType extends EnumType
{
    const OPEN        = 'open';
    const IN_PROGRESS = 'in_progress';
    const CLOSED      = 'closed';

    protected $name = 'issue_status_enum';
    protected $values = [self::OPEN, self::IN_PROGRESS, self::CLOSED];
}
