<?php

namespace AppBundle\Tests\Unit\EventListener\Event;

use AppBundle\Entity\User;
use AppBundle\EventListener\Event\CreateUserEvent;

class CreateUserEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CreateUserEvent
     */
    protected $object;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var string
     */
    protected $password;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->user = new User();
        $this->password = 'super secret';
        $this->object = new CreateUserEvent($this->user, $this->password);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\EventListener\Event\CreateUserEvent::getUser
     */
    public function testGetUser()
    {
        $this->assertEquals($this->user, $this->object->getUser());
    }

    /**
     * @covers AppBundle\EventListener\Event\CreateUserEvent::getPassword
     */
    public function testGetPassword()
    {
        $this->assertEquals($this->password, $this->object->getPassword());
    }
}
