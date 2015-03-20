<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserFormService extends AbstractControllerService
{
    /** @var FormFactoryInterface */
    protected $factory;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(EntityManager $manager, FormFactoryInterface $factory, TranslatorInterface $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        parent::__construct($manager);
    }

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
