<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Form\AbstractFormService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserFormService extends AbstractFormService
{
    public function getAddForm()
    {
        $user = new User();
        return $this->factory->create('app_user', $user)->add(
            'password',
            'text',
            [
                'required' => false,
                'label' => $this->translator->trans('app.password_will_be_generated')
            ]
        );
    }
}
