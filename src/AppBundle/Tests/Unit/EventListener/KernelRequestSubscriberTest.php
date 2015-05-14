<?php

namespace AppBundle\Tests\Unit\EventListener;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\EventListener\KernelRequestSubscriber;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class KernelRequestSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KernelRequestSubscriber
     */
    protected $object;

    /**
     * @var TokenStorage
     */
    protected $tokenStorage;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $twigEnvironmentMock;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->tokenStorage = new TokenStorage();
        $this->tokenStorage->setToken(new UsernamePasswordToken('admin', null, 'main', [Role::ADMINISTRATOR]));

        $this->twigEnvironmentMock = $this->getMockBuilder('\Twig_Environment')->getMock();
        $this->object = new KernelRequestSubscriber($this->twigEnvironmentMock, $this->tokenStorage);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\EventListener\KernelRequestSubscriber::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        $this->assertSame(
            [KernelEvents::REQUEST => ['onKernelRequest', KernelRequestSubscriber::EVENT_PRIORITY]],
            $this->object->getSubscribedEvents()
        );
    }

    /**
     * @covers AppBundle\EventListener\KernelRequestSubscriber::onKernelRequest
     */
    public function testOnKernelRequest()
    {
        $timezone = 'Europe/Kiev';
        $user = (new User())->setTimezone($timezone);
        $this->tokenStorage->getToken()->setUser($user);

        $twigCoreMock = $this->getMockBuilder('\Twig_Extension_Core')
            ->getMock();

        $twigCoreMock->expects($this->once())
            ->method('setTimezone')
            ->with($timezone);

        $this->twigEnvironmentMock->expects($this->once())
            ->method('getExtension')
            ->with('core')
            ->willReturn($twigCoreMock);

        $event = $this->getMockBuilder('Symfony\Component\HttpKernel\Event\GetResponseEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object->onKernelRequest($event);
    }
}
