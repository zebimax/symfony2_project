<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * IssueResolution
 *
 * @ORM\Table(name="bt_issue_resolution")
 * @ORM\Entity
 */
class IssueResolution extends AbstractIssueProperty
{
    const FIXED            = 'fixed';
    const WON_T_FIX        = 'won_t_fix';
    const DUPLICATE        = 'duplicate';
    const INCOMPLETE       = 'incomplete';
    const CANNOT_REPRODUCE = 'cannot_reproduce';
    const DONE             = 'done';
    const WON_T_DO         = 'won_t_do';
}
