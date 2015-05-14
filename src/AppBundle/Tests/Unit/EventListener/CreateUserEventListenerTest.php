<?php

namespace AppBundle\Tests\Unit\EventListener;

use AppBundle\Entity\User;
use AppBundle\EventListener\CreateUserEventListener;
use AppBundle\EventListener\Event\CreateUserEvent;
use PHPUnit_Framework_MockObject_MockObject;

class CreateUserEventListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CreateUserEventListener
     */
    protected $object;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $mailMock;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->mailMock = $this->getMockBuilder('AppBundle\Service\MailService')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new CreateUserEventListener($this->mailMock);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\EventListener\CreateUserEventListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [CreateUserEvent::CREATE_USER_EVENT => 'onAppCreateUserEvent'],
            $this->object->getSubscribedEvents()
        );
    }

    /**
     * @covers AppBundle\EventListener\CreateUserEventListener::onAppCreateUserEvent
     */
    public function testOnAppCreateUserEvent()
    {
        $user = new User();
        $password = 'secret';

        $event = new CreateUserEvent($user, $password);
        $this->mailMock
            ->expects($this->once())
            ->method('sendCreateUserMail')
            ->with($user, $password);
        $this->object->onAppCreateUserEvent($event);
    }
}
