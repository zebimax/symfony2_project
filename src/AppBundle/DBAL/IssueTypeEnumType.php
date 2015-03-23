<?php

namespace AppBundle\DBAL;

class IssueTypeEnumType extends EnumType
{
    const BUG      = 'bug';
    const SUB_TASK = 'sub_task';
    const TASK     = 'task';
    const STORY    = 'story';

    protected $name = 'issue_type_enum';
    protected $values = [self::BUG, self::SUB_TASK, self::TASK, self::STORY];
}
