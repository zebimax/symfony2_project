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
    public function setIssue($issue)
    {
        $this->issue = $issue;

        return $this;
    }
}
