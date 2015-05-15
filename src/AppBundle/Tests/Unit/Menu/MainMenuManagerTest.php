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
