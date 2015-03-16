<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class MenuBuilder
{
    private $factory;
    private $translator;

    /**
     * @param FactoryInterface $factory
     * @param Translator $translator
     */
    public function __construct(FactoryInterface $factory, Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function createMainMenu(MainMenuManager $mainMenuManager)
    {
        $menu = $this->factory->createItem('root');

        $mainMenuManager->manageMenuItems($menu);
        return $menu;
    }

    public function createLogoutMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild($this->translator->trans('logout_menu.logout'), array('route' => 'logout'));
        return $menu;
    }
}
