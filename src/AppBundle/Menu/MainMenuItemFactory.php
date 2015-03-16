<?php

namespace AppBundle\Menu;

class MainMenuItemFactory
{
    /**
     * @param array $config
     * @param array $subItems
     * @param array $parameters
     * @return MainMenuItem
     */
    public static function createMainMenuItem(array $config, array $subItems = [], array $parameters = [])
    {
        return new MainMenuItem($config, $subItems, $parameters);
    }
}
