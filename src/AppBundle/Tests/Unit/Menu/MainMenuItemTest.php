<?php

namespace AppBundle\Tests\Unit\Menu;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Menu\MainMenuItem;
use AppBundle\Menu\Route\UserIdParameterProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MainMenuItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MainMenuItem
     */
    protected $object;

    protected function setUp()
    {
        $storage = new TokenStorage();
        $token = new UsernamePasswordToken('admin', null, 'main', [Role::ADMINISTRATOR]);

        $token->setUser(new User());
        $storage->setToken($token);
        $userIdParameterProvider = new UserIdParameterProvider($storage);

        $this->object = new MainMenuItem(
            ['name' => 'profile', 'label' => 'profile_label'],
            [new MainMenuItem(
                [
                    'name' => 'profile_sub_item',
                    'label' => 'profile_sub_item_label',
                    'route' => 'profile_sub_item_route',
                    'route_parameters' => [$userIdParameterProvider],
                ]
            )]
        );
    }

    /**
     * @covers AppBundle\Menu\MainMenuItem::getSubItems
     */
    public function testGetSubItems()
    {
        $subItems = $this->object->getSubItems();
        $this->assertEquals(1, count($subItems));
        $this->assertInstanceOf('AppBundle\Menu\MainMenuItemInterface', $subItems[0]);
    }

    /**
     * @covers AppBundle\Menu\MainMenuItem::getName
     */
    public function testGetName()
    {
        $this->assertEquals('profile', $this->object->getName());
    }

    /**
     * @covers AppBundle\Menu\MainMenuItem::getLabel
     */
    public function testGetLabel()
    {
        $this->assertEquals('profile_label', $this->object->getLabel());
    }

    /**
     * @covers AppBundle\Menu\MainMenuItem::getRouteParameters
     */
    public function testGetRouteParameters()
    {
        $this->assertEmpty($this->object->getRouteParameters());
    }

    /**
     * @covers AppBundle\Menu\MainMenuItem::getRoute
     */
    public function testGetRoute()
    {
        $this->assertSame(null, $this->object->getRoute());
    }
}
