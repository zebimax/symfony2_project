<?php

namespace AppBundle\Menu;

class MainMenuManager
{
    private $menuItems = [];

    /**
     * @param MainMenuItemInterface[] $menuItems
     */
    public function __construct(array $menuItems = [])
    {
        $this->setMenuItems($menuItems);
    }

    /**
     * @return MainMenuItemInterface[]
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    /**
     * @param array $menuItems
     */
    private function setMenuItems($menuItems)
    {
        foreach ($menuItems as $item) {
            if ($item instanceof MainMenuItemInterface) {
                $this->menuItems[] = $item;
            }
        }
    }


}
