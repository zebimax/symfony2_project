<?php

namespace AppBundle\Tests\Unit\Menu;

use AppBundle\Menu\MainMenuManager;
use AppBundle\Menu\MainMenuItem;

class MainMenuManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MainMenuManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new MainMenuManager(
            [
                'test',
                new MainMenuItem(['name' => 'test', 'label' => 'test']),
                new MainMenuItem(['name' => 'test', 'label' => 'test']),
            ]
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\Menu\MainMenuManager::getMenuItems
     */
    public function testGetMenuItems()
    {
        $items = $this->object->getMenuItems();
        $this->assertEquals(2, count($items));
        $this->assertInstanceOf('AppBundle\Menu\MainMenuItemInterface', $items[0]);
        $this->assertInstanceOf('AppBundle\Menu\MainMenuItemInterface', $items[1]);
    }
}
