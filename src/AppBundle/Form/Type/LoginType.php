<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    const MIN_PASSWORD_LENGTH = 3;
    const MIN_USERNAME_LENGTH = 3;
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
            ->add(
                'username',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('login_form.username'),
                    'constraints'   => [
                        new NotBlank(),
                        new Length(['min' => self::MIN_USERNAME_LENGTH]),
                    ],
                ]
            )
            ->add(
                'password',
                'password',
                [
                    'required' => true,
                    'label' => $this->translator->trans('login_form.password'),
                    'constraints'   => [
                        new NotBlank(),
                        new Length(['min' => self::MIN_PASSWORD_LENGTH]),
                    ],
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
