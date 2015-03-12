<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder extends ContainerAware
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

    public function createMainMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');

        $users = $menu->addChild($this->translator->trans('main_menu.users'));
        $users->addChild($this->translator->trans('main_menu.users.create'), array('route' => 'app_user_add'));
        $users->addChild($this->translator->trans('main_menu.users.list'), array('route' => 'app_user_list'));

        return $menu;
    }

    public function createLogoutMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild($this->translator->trans('logout_menu.logout'), array('route' => 'logout'));
        return $menu;
    }
}
