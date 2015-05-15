<?php

namespace AppBundle\Tests\Unit\Menu\Route;

use AppBundle\Entity\Role;
use AppBundle\Menu\Route\UserIdParameterProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class UserIdParameterProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers AppBundle\Menu\Route\UserIdParameterProvider::getRouteParameters
     */
    public function testGetRouteParameters()
    {
        $storage = new TokenStorage();
        $token = new UsernamePasswordToken('admin', null, 'main', [Role::ADMINISTRATOR]);

        $user = $this
            ->getMockBuilder('AppBundle\Entity\User')
            ->getMock();
        $user->expects($this->once())->method('getId');

        $token->setUser($user);
        $storage->setToken($token);
        $object = new UserIdParameterProvider($storage);
        $this->assertArrayHasKey('id', $object->getRouteParameters());
    }
}
