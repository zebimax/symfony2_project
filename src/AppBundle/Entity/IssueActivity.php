<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bt_activity")
 * @ORM\Entity
 */
class IssueActivity extends AbstractIssueEvent
{
    const CREATE_ISSUE        = 'create_issue';
    const CHANGE_ISSUE_STATUS = 'change_issue_status';
    const COMMENT_ISSUE       = 'comment_issue';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $type;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $details;
    /**
     * @var integer
     */

    /**
     * Set type
     *
     * @param string $type
     * @return IssueActivity
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set details
     *
     * @param array $details
     * @return IssueActivity
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
}
