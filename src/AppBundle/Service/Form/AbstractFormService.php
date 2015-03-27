<?php

namespace AppBundle\Service\Form;

use AppBundle\Service\AbstractControllerService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class AbstractFormService extends AbstractControllerService
{
    /** @var FormFactoryInterface */
    protected $factory;

    /**
     * @param EntityManager $manager
     * @param TranslatorInterface $translator
     * @param FormFactoryInterface $factory
     */
    public function __construct(EntityManager $manager, TranslatorInterface $translator, FormFactoryInterface $factory)
    {
        $this->factory = $factory;
        parent::__construct($manager, $translator);
    }
}
