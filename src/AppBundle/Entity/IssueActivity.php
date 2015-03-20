<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="bt_activity")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\IssueActivities")
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
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="activities")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $issue;

    /**
     * @var string
     */
    private $message;

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

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     * @return $this
     */
    public function setIssue(Issue $issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }
}
