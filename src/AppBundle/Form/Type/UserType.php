<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
     * {@inheritdoc}
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
                'data_class'        => 'AppBundle\Entity\User',
                'validation_groups' => ['add', 'Default'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formFactory = $builder->getFormFactory();
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($formFactory) {
            $form = $event->getForm();
            $user = $event->getData();
            if ($user instanceof User && $user->getId() === null) {
                $form->add(
                    'password',
                    'password',
                    [
                        'required' => false,
                        'label'    => $this->translator->trans('app.password_will_be_generated'),
                    ]
                );
            }
        });
        $builder
            ->add(
                'email',
                'email',
                [
                    'required' => true,
                    'label'    => $this->translator->trans('app.email'),
                ]
            )
            ->add(
                'username',
                'text',
                [
                    'required' => true,
                    'label'    => $this->translator->trans('app.username'),
                ]
            )
            ->add(
                'fullname',
                'text',
                [
                    'required' => true,
                    'label'    => $this->translator->trans('app.fullName'),
                ]
            )
            ->add(
                'timezone',
                'timezone',
                [
                    'required' => false,
                    'label'    => $this->translator->trans('app.timezone'),
                ]
            )
            ->add(
                'file',
                'file',
                [
                    'required' => false,
                    'label'    => $this->translator->trans('app.avatar'),
                ]
            );
    }
}
