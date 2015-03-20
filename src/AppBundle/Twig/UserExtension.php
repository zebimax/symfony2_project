<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Role;

class UserExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_user_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('renderPrimaryRole', [$this, 'renderPrimaryRole']),
            new \Twig_SimpleFilter('renderIsActive', [$this, 'renderIsActive'])
        ];
    }

    /**
     * @param $userRole
     * @return string
     */
    public function renderPrimaryRole($userRole)
    {
        $role = 'role.undefined';
        switch ($userRole) {
            case Role::ADMINISTRATOR:
                $role = 'role.administrator';
                break;
            case Role::MANAGER:
                $role = 'role.manager';
                break;
            case Role::OPERATOR:
                $role = 'role.operator';
                break;
            default:
                break;
        }
        return $this->translator->trans($role);
    }

    /**
     * @param $isActive
     * @return string
     */
    public function renderIsActive($isActive)
    {
        return $isActive ? '+' : '-';
    }
}
