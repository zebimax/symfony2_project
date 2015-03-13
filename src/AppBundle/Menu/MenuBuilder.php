<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder //extends ContainerAware
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

    public function createMainMenu(RequestStack $requestStack, TokenStorage $storage)
    {
        $userId = $storage->getToken()->getUser()->getId();

        $menu = $this->factory->createItem('root');

        $issues = $menu->addChild($this->translator->trans('main_menu.issues'));
        $issues->addChild($this->translator->trans('main_menu.issues.create'), ['route' => 'app_issue_add']);
        $issues->addChild($this->translator->trans('main_menu.issues.list'), ['route' => 'app_issue_list']);

        $projects = $menu->addChild($this->translator->trans('main_menu.projects'));
        $projects->addChild($this->translator->trans('main_menu.projects.create'), ['route' => 'app_project_add']);
        $projects->addChild($this->translator->trans('main_menu.projects.list'), ['route' => 'app_project_list']);

        $users = $menu->addChild($this->translator->trans('main_menu.users'));
        $users->addChild($this->translator->trans('main_menu.users.create'), ['route' => 'app_user_add']);
        $users->addChild($this->translator->trans('main_menu.users.list'), ['route' => 'app_user_list']);

        $profile = $menu->addChild($this->translator->trans('main_menu.profile'));
        $profile->addChild($this->translator->trans('main_menu.profile.view'), [
            'route' => 'app_profile_view',
            'routeParameters' => array('id' => $userId)
        ]);
        $profile->addChild($this->translator->trans('main_menu.profile.edit'), [
            'route' => 'app_profile_edit',
            'routeParameters' => array('id' => $userId)
        ]);



        return $menu;
    }

    public function createLogoutMenu(RequestStack $requestStack)
    {
        $menu = $this->factory->createItem('root');
        $menu->addChild($this->translator->trans('logout_menu.logout'), array('route' => 'logout'));
        return $menu;
    }
}
