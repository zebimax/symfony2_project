<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserType extends AbstractType
{
    /**
     * @var TranslatorInterface
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
        $resolver->setDefaults(
            [
                'data_class' => 'AppBundle\Entity\User',
                'validation_groups' => ['add', 'Default'],
            ]
        );
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
                    'label' => $this->translator->trans('app.email'),
                ]
            )
            ->add(
                'username',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.username'),
                ]
            )
            ->add(
                'fullname',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.fullName'),
                ]
            )
            ->add(
                'timezone',
                'timezone',
                [
                    'required' => false,
                    'label' => $this->translator->trans('app.timezone'),
                ]
            )
            ->add(
                'file',
                'file',
                [
                    'required' => false,
                    'label' => $this->translator->trans('app.avatar'),
                ]
            );
    }
}
