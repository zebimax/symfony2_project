<?php

namespace AppBundle\Tests\Unit\EventListener\Event;

use AppBundle\Entity\Issue;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\Entity\IssueActivity;

class IssueActivityEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueActivityEvent
     */
    protected $object;

    /**
     * @var IssueActivity
     */
    protected $activity;

    protected function setUp()
    {
        $this->activity = new IssueActivity(new Issue(), new User());
        $this->object = new IssueActivityEvent($this->activity);
    }

    /**
     * @covers AppBundle\EventListener\Event\IssueActivityEvent::getActivity
     */
    public function testGetActivity()
    {
        $this->assertEquals($this->activity, $this->object->getActivity());
    }
}
