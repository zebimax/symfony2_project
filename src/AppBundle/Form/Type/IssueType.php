<?php

namespace AppBundle\Form\Type;

use AppBundle\DBAL\IssuePriorityEnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class IssueType extends AbstractType
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
        return 'app_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        return [
            'data_class' => 'AppBundle\Entity\Issue'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label' => $this->translator->trans('app.summary')
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label' => $this->translator->trans('app.description')
                ]
            )
            ->add(
                'priority',
                'choice',
                [
                    'choice_list' => new ChoiceList(
                        [
                            IssuePriorityEnumType::TRIVIAL,
                            IssuePriorityEnumType::MINOR,
                            IssuePriorityEnumType::MAJOR,
                            IssuePriorityEnumType::BLOCKER
                        ],
                        [
                            $this->translator->trans('app.issue.priorities.trivial'),
                            $this->translator->trans('app.issue.priorities.minor'),
                            $this->translator->trans('app.issue.priorities.major'),
                            $this->translator->trans('app.issue.priorities.blocker')
                        ]
                    ),
                    'required' => true,
                    'label' => $this->translator->trans('app.issue.priority')
                ]
            )
            ->add(
                'assignee',
                'entity',
                array(
                    'label' => $this->translator->trans('app.issue.assignee'),
                    'property_path' => 'assignee',
                    'class'         => 'AppBundle:User',
                    'property'      => 'username',
                    'required' => false,
                    'attr' => array('class'=>'form-control')
                )
            );
    }
}