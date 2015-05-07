<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var TranslatorInterface */
    private $translator;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * @param FactoryInterface              $factory
     * @param TranslatorInterface           $translator
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FactoryInterface $factory,
        TranslatorInterface $translator,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->factory              = $factory;
        $this->translator           = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param MainMenuManager $mainMenuManager
     *
     * @return ItemInterface
     */
    public function createMainMenu(MainMenuManager $mainMenuManager)
    {
        $menu = $this->factory->createItem('root');
        $this->addItems($menu, $mainMenuManager->getMenuItems());

        return $menu;
    }

    /**
     * @return ItemInterface
     */
    public function createLogoutMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild($this->translator->trans('logout_menu.logout'), ['route' => 'logout']);

        return $menu;
    }

    /**
     * @param ItemInterface           $menuBuilder
     * @param MainMenuItemInterface[] $menuItems
     */
    private function addItems(ItemInterface $menuBuilder, array $menuItems)
    {
        foreach ($menuItems as $item) {
            if ($this->authorizationChecker->isGranted($item->getName())) {
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
