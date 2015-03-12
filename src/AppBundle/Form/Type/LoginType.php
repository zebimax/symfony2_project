<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;

class LoginType extends AbstractType
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('login_form.username'),
                ]
            )
            ->add(
                'password',
                'password',
                [
                    'required' => true,
                    'label' => $this->translator->trans('login_form.password'),
                ]
            )
            ->add(
                'submit',
                'submit',
                [
                    'label' => $this->translator->trans('app.button.submit'),
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_login';
    }
}
