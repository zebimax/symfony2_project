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
    public function __construct(Issue $issue, User $user)
    {
        $this->issue = $issue;
        $this->issue->addComment($this);
        $this->user = $user;
        parent::__construct();
    }
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
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->activity = (new IssueActivity($this->issue, $this->user))
            ->setType(IssueActivity::COMMENT_ISSUE);
        $this->issue->addCollaborator($this->user);
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }
}
