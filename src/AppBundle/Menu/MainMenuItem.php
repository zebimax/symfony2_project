<?php

namespace AppBundle\Menu;

use AppBundle\Menu\Route\ParameterProviderInterface;

class MainMenuItem implements MainMenuItemInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $label;

    /** @var string */
    protected $route;

    /** @var array */
    protected $subItems = [];

    /** @var array */
    protected $routeParameters = [];

    /**
     * @param array $config
     * @param array $subItems
     */
    public function __construct(array $config, array $subItems = [])
    {
        $this->setConfigs($config);
        $this->setSubItems($subItems);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubItems()
    {
        return $this->subItems;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param array $subItems
     */
    final private function setSubItems(array $subItems = [])
    {
        foreach ($subItems as $subItem) {
            if ($subItem instanceof MainMenuItemInterface) {
                $this->subItems[] = $subItem;
            }
        }
    }

    /**
     * @param array $configs
     */
    final private function setConfigs(array $configs)
    {
        if (!array_key_exists('name', $configs)) {
            throw new \InvalidArgumentException('MainMenuItem must have "name" config');
        }
        if (!array_key_exists('label', $configs)) {
            throw new \InvalidArgumentException('MainMenuItem must have "label" config');
        }
        if (array_key_exists('route', $configs)) {
            $this->route = $configs['route'];
        }
        if (array_key_exists('route_parameters', $configs) && is_array($configs['route_parameters'])) {
            foreach ($configs['route_parameters'] as $routeParameterProvider) {
                if ($routeParameterProvider instanceof ParameterProviderInterface) {
                    $this->routeParameters = array_merge(
                        $this->routeParameters,
                        $routeParameterProvider->getRouteParameters()
                    );
                }
            }
        }
        $this->name = $configs['name'];
        $this->label = $configs['label'];
    }
}
