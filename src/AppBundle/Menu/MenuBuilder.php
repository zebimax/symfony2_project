<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MenuBuilder
{
    private $factory;
    private $translator;
    private $authorizationChecker;

    /**
     * @param FactoryInterface $factory
     * @param Translator $translator
     * @param AuthorizationChecker $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, Translator $translator, AuthorizationChecker $authorizationChecker)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMainMenu(MainMenuManager $mainMenuManager)
    {
        $menu = $this->factory->createItem('root');
        $this->addItems($menu, $mainMenuManager->getMenuItems());
        return $menu;
    }

    public function createLogoutMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild($this->translator->trans('logout_menu.logout'), array('route' => 'logout'));
        return $menu;
    }

    /**
     * @param ItemInterface $menuBuilder
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
