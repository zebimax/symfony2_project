<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * IssueType
 *
 * @ORM\Table(name="bt_issue_type")
 * @ORM\Entity
 */
class IssueType extends AbstractIssueProperty
{
    const BUG      = 'bug';
    const SUB_TASK = 'sub_task';
    const TASK     = 'task';
    const STORY    = 'story';
}
