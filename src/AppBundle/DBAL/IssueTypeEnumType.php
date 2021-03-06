<?php

namespace AppBundle\DBAL;

class IssueTypeEnumType extends EnumType
{
    const BUG      = 'bug';
    const SUB_TASK = 'sub_task';
    const TASK     = 'task';
    const STORY    = 'story';

    const TYPE_NAME = 'issue_type_enum';

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
        return [self::BUG, self::SUB_TASK, self::TASK, self::STORY];
    }
}
