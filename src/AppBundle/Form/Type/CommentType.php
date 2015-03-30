<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CommentType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

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
        return 'app_comment';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Comment']);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'body',
            'textarea',
            [
                'required' => true,
                'label' => false,
                'attr' => ['placeholder' => $this->translator->trans('app.placeholders.your_comment')],
            ]
        );
    }
}
