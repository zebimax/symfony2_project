<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * IssueStatus
 *
 * @ORM\Table(name="bt_issue_status")
 * @ORM\Entity
 */
class IssueStatus extends AbstractIssueProperty
{
    const OPEN        = 'open';
    const IN_PROGRESS = 'in_progress';
    const CLOSED      = 'closed';
}
