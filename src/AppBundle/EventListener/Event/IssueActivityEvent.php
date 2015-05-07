<?php

namespace AppBundle\EventListener\Event;

use AppBundle\Entity\IssueActivity;
use Symfony\Component\EventDispatcher\Event;

class IssueActivityEvent extends Event
{
    const ISSUE_ACTIVITY = 'app.issue_activity';

    /** @var IssueActivity */
    protected $activity;

    /**
     * @param IssueActivity $activity
     */
    public function __construct(IssueActivity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return IssueActivity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
