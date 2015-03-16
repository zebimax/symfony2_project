<?php

namespace AppBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Translation\TranslatorInterface;

class MainMenuManager
{
    private $menuItems = [];
    private $authorizationChecker;
    private $translator;

    /**
     * @param AuthorizationChecker $authorizationChecker
     * @param TranslatorInterface $translatorInterface
     * @param array $menuItems
     */
    public function __construct(
        AuthorizationChecker $authorizationChecker,
        TranslatorInterface $translatorInterface,
        array $menuItems = []
    ) {
        $this->menuItems = $menuItems;
        $this->authorizationChecker = $authorizationChecker;
        $this->translator = $translatorInterface;
    }

    /**
     * @param ItemInterface $menuBuilder
     */
    public function manageMenuItems(ItemInterface $menuBuilder)
    {
        $this->addItems($menuBuilder, $this->menuItems);
    }

    /**
     * @param ItemInterface $menuBuilder
     * @param array $menuItems
     */
    private function addItems(ItemInterface $menuBuilder, array $menuItems)
    {
        foreach ($menuItems as $item) {
            if ($item instanceof MainMenuItemInterface && $this->authorizationChecker->isGranted($item->getName())) {
                $itemOptions = ['routeParameters' => $item->getRouteParameters()];

                if ($route = $item->getRoute()) {
                    $itemOptions['route'] = $route;
                }

                $menuBuilderItem = $menuBuilder->addChild(
                    $this->translator->trans($item->getLabel()),
                    $itemOptions
                );
                $this->addItems($menuBuilderItem, $item->getSubItems());
            }
        }
    }
}
