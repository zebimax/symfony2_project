<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueProperty;
use Doctrine\ORM\Mapping as ORM;

/**
 * IssuePriority
 *
 * @ORM\Table(name="bt_issue_priority")
 * @ORM\Entity
 */
class IssuePriority extends AbstractIssueProperty
{
    const TRIVIAL = 'trivial';
    const MINOR   = 'minor';
    const MAJOR   = 'major';
    const BLOCKER = 'blocker';
}
