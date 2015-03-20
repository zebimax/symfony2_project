<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\Translator;

class UserType extends AbstractType
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
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'app_user';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return [
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => ['add']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                'email',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.email')
                ]
            )
            ->add(
                'username',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.username')
                ]
            )
            ->add(
                'fullname',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.fullName')
                ]
            )
            ->add(
                'file',
                'file',
                [
                    'required' => false,
                    'label' => $this->translator->trans('app.avatar')
                ]
            )
            ->add('roles', 'entity', [
                'required' => true,
                'class' => 'AppBundle:Role',
                'property' => 'name',
                'multiple' => true,
                'attr' => array('class' => 'form-control')
            ]);
    }
}
