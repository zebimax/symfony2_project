<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Form\AbstractFormService;

class UserFormService extends AbstractFormService
{
    /**
     * @param User $user
     * @param User $currentUser
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getUserForm(User $user, User $currentUser)
    {
        $builder =  $this->factory->create('app_user', $user);
        if ($user->getId() === null) {
            $builder->add(
                'password',
                'text',
                [
                    'required' => false,
                    'label' => $this->translator->trans('app.password_will_be_generated'),
                ]
            );
        }
        if ($currentUser->isAdmin()) {
            $builder->add(
                'roles',
                'entity',
                [
                    'required' => true,
                    'class' => 'AppBundle:Role',
                    'property' => 'name',
                    'multiple' => true,
                    'attr' => array('class' => 'form-control'),
                ]
            );
        }

        return $builder;
    }

    /**
     * @param User $user
     */
    public function saveUser(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }
}
