<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Role;
use Symfony\Component\Translation\Translator;

class UserExtension extends \Twig_Extension
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

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

    public function renderIsActive($isActive)
    {
        return $isActive ? '+' : '-';
    }
}
