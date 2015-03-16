<?php

namespace AppBundle\Menu\Route;

interface ParameterProviderInterface
{
    /**
     * @return array
     */
    public function getRouteParameters();
}
