<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Choice;

class ProjectMemberType extends AbstractType
{
    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', 'choice', [
                'choices' => $options['data'],
                'required' => true,
                'attr' => array('class' => 'form-control'),
                'constraints' => new Choice(['choices' => array_keys($options['data'])]),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_project_member';
    }
}
