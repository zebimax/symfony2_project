<?php

namespace AppBundle\Tests\Unit\Service;

use AppBundle\Entity\User;
use AppBundle\EventListener\Event\CreateUserEvent;
use AppBundle\Service\UserPasswordService;

class UserPasswordServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserPasswordService
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $generatorMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $encoderMock;

    protected function setUp()
    {
        $this->encoderMock   = $this
            ->getMockBuilder('Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface')
            ->getMock();
        $this->generatorMock = $this
            ->getMockBuilder('Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator')
            ->disableOriginalConstructor()
            ->getMock();
        $this->object        = new UserPasswordService($this->encoderMock, $this->generatorMock);
    }

    /**
     * @covers       AppBundle\Service\UserPasswordService::setUserPassword
     * @dataProvider setUserPasswordDataProvider
     * @param string $plainPass
     * @param bool   $willGenerate
     */
    public function testSetUserPassword($plainPass, $willGenerate)
    {
        $encodedPass    = 'encoded';
        $length         = 5;
        $user           = new User();
        $pass           = $plainPass;
        $dispatcherMock = $this
            ->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')
            ->getMock();
        $dispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->withConsecutive(
                [
                    CreateUserEvent::CREATE_USER_EVENT,
                    new \PHPUnit_Framework_Constraint_IsInstanceOf(
                        'AppBundle\EventListener\Event\CreateUserEvent'
                    )
                ]
            );
        $this->object->setEventDispatcher($dispatcherMock);
        if ($willGenerate) {
            $generated = 'generated';
            $this->generatorMock
                ->expects($this->once())
                ->method('setLength')
                ->with($length)
                ->willReturnSelf();
            $this->generatorMock
                ->expects($this->once())
                ->method('generatePassword')
                ->willReturn($generated);
            $pass = $generated;
        }
        $this->encoderMock
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, $pass)
            ->willReturn($encodedPass);
        $this->object->setUserPassword($user, $plainPass, $length);
    }

    /**
     * @return array
     */
    public function setUserPasswordDataProvider()
    {
        return [
            'will_generate'     => ['plainPass' => '', 'willGenerate' => true],
            'will_not_generate' => ['plainPass' => 'plain_pass', 'willGenerate' => false]
        ];
    }
}
