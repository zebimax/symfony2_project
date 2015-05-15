<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\Service\CommentService;

class CommentServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CommentService
     */
    protected $object;

    protected function setUp()
    {
        $emMock          = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $formFactoryMock = $this
            ->getMockBuilder('Symfony\Component\Form\FormFactoryInterface')
            ->getMock();
        $translatorMock  = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();

        $this->object = new CommentService($emMock, $translatorMock, $formFactoryMock);
    }

    /**
     * @covers AppBundle\Service\CommentService::addComment
     */
    public function testAddComment()
    {
        $dispatcherMock = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();
        $dispatcherMock
            ->expects($this->at(0))
            ->method('dispatch')
            ->withConsecutive(
                [
                    IssueActivityEvent::ISSUE_ACTIVITY,
                    new \PHPUnit_Framework_Constraint_IsInstanceOf(
                        'AppBundle\EventListener\Event\IssueActivityEvent'
                    )
                ]
            );
        $this->object->setEventDispatcher($dispatcherMock);

        $issue   = new Issue();
        $user    = new User();
        $comment = (new Comment())
            ->setIssue($issue)
            ->setUser($user);

        $this->object->addComment($comment);

        /** @var IssueActivity $activity */
        $activity = $issue->getActivities()->current();

        $this->assertTrue($issue->getCollaborators()->contains($user));
        $this->assertTrue($activity->getType() === IssueActivity::COMMENT_ISSUE);
        $this->assertEquals($activity->getCreated(), $comment->getCreated());
    }
}
