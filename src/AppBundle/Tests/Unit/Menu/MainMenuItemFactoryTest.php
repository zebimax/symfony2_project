<?php

namespace AppBundle\Tests\Unit\Menu;

use AppBundle\Menu\MainMenuItemFactory;

class MainMenuItemFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MainMenuItemFactory
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new MainMenuItemFactory();
    }

    /**
     * @covers AppBundle\Menu\MainMenuItemFactory::createMainMenuItem
     */
    public function testCreateMainMenuItem()
    {
        $this->assertInstanceOf(
            'AppBundle\Menu\MainMenuItemInterface',
            $this->object->createMainMenuItem(['name' => 'test', 'label' => 'test'])
        );
    }
}
