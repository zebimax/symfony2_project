<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service\Form\AbstractFormService;
use Symfony\Component\Form\FormInterface;

class UserFormService extends AbstractFormService
{
    /**
     * @param User $user
     * @param User $currentUser
     *
     * @return FormInterface
     */
    public function getUserForm(User $user, User $currentUser)
    {
        $builder = $this->factory->create('app_user', $user);
        if ($currentUser->isAdmin()) {
            $builder->add(
                'roles',
                'entity',
                [
                    'required' => true,
                    'class' => 'AppBundle:Role',
                    'property' => 'name',
                    'multiple' => true,
                    'attr' => ['class' => 'form-control'],
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
