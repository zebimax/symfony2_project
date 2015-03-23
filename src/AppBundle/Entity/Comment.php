<?php

namespace AppBundle\Entity;

use AppBundle\Entity\MappedSuperClass\AbstractIssueEvent;
use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="bt_comment")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Comment extends AbstractIssueEvent
{
    /**
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $issue;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var IssueActivity
     *
     * @ORM\OneToOne(targetEntity="IssueActivity", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $activity;

    /**
     * Set body
     *
     * @param string $body
     * @return Comment
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return IssueActivity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @param IssueActivity $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->activity = (new IssueActivity())
            ->setType(IssueActivity::COMMENT_ISSUE)
            ->setUser($this->user);
        $this->issue->addCollaborator($this->user);
    }
}
