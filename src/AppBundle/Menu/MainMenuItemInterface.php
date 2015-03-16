<?php

namespace AppBundle\Menu;

interface MainMenuItemInterface
{
    /**
     * @return MainMenuItemInterface[]
     */
    public function getSubItems();

    /**
     * @return array
     */
    public function getRouteParameters();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getRoute();
}
