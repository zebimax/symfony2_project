<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

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
            ->add('users', 'entity', [
                'required' => true,
                'attr' => array('class' => 'form-control')
            ])
            ->add(
                'submit',
                'submit',
                [
                    'label' => $this->translator->trans('app.button.submit')
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_project_member';
    }
}
