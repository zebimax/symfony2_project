<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;

class ProjectMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'users',
                'choice',
                [
                    'choices'     => $options['data'],
                    'required'    => true,
                    'attr'        => ['class' => 'form-control'],
                    'constraints' => new Choice(['choices' => array_keys($options['data'])]),
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
