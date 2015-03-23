<?php

namespace AppBundle\DBAL;

class IssueResolutionEnumType extends EnumType
{
    const FIXED            = 'fixed';
    const WON_T_FIX        = 'won_t_fix';
    const DUPLICATE        = 'duplicate';
    const INCOMPLETE       = 'incomplete';
    const CANNOT_REPRODUCE = 'cannot_reproduce';
    const DONE             = 'done';
    const WON_T_DO         = 'won_t_do';

    protected $name = 'issue_resolution_enum';
    protected $values = [
        self::FIXED,
        self::WON_T_DO,
        self::DUPLICATE,
        self::INCOMPLETE,
        self::CANNOT_REPRODUCE,
        self::DONE,
        self::WON_T_DO,
        null
    ];
}
