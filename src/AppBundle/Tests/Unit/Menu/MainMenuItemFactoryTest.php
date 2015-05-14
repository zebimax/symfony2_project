<?php

namespace AppBundle\Tests\Unit\Menu;

use AppBundle\Menu\MainMenuItemFactory;

class MainMenuItemFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MainMenuItemFactory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new MainMenuItemFactory();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
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
