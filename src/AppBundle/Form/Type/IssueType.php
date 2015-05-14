<?php

namespace AppBundle\Form\Type;

use AppBundle\DBAL\IssuePriorityEnumType;
use AppBundle\DBAL\IssueResolutionEnumType;
use AppBundle\DBAL\IssueStatusEnumType;
use AppBundle\DBAL\IssueTypeEnumType;
use AppBundle\Entity\Issue;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
     * {@inheritdoc}
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
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Issue']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form  = $event->getForm();
                $issue = $event->getData();
                if ($issue instanceof Issue) {
                    if ($issue->getParent() === null && $this->isIssueTypeChangeable($issue)) {
                        $this->addTypeField($form);
                    }
                    if ($issue->getStatus() !== null) {
                        $this->addStatusField($form);
                    }
                    if ($issue->getStatus() === IssueStatusEnumType::IN_PROGRESS) {
                        $this->addResolutionField($form);
                    }
                }
            }
        );
        $builder
            ->add(
                'summary',
                'text',
                [
                    'required' => true,
                    'label'    => $this->translator->trans('app.summary'),
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => false,
                    'label'    => $this->translator->trans('app.description'),
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
                            IssuePriorityEnumType::BLOCKER,
                        ],
                        [
                            $this->translator->trans('app.issue.priorities.trivial'),
                            $this->translator->trans('app.issue.priorities.minor'),
                            $this->translator->trans('app.issue.priorities.major'),
                            $this->translator->trans('app.issue.priorities.blocker'),
                        ]
                    ),
                    'required'    => true,
                    'label'       => $this->translator->trans('app.issue.priority'),
                ]
            );
    }

    /**
     * @param FormInterface $form
     *
     * @return FormInterface
     */
    protected function addTypeField(FormInterface $form)
    {
        return $form
            ->add(
                'type',
                'choice',
                [
                    'choice_list' => new ChoiceList(
                        [
                            IssueTypeEnumType::STORY,
                            IssueTypeEnumType::BUG,
                            IssueTypeEnumType::TASK,
                        ],
                        [
                            $this->translator->trans('app.issue.types.story'),
                            $this->translator->trans('app.issue.types.bug'),
                            $this->translator->trans('app.issue.types.task'),
                        ]
                    ),
                    'required'    => true,
                    'label'       => $this->translator->trans('app.issue.type'),
                ]
            );
    }

    /**
     * @param FormInterface $form
     *
     * @return FormInterface
     */
    protected function addResolutionField(FormInterface $form)
    {
        return $form
            ->add(
                'resolution',
                'choice',
                [
                    'choice_list' => new ChoiceList(
                        [
                            IssueResolutionEnumType::FIXED,
                            IssueResolutionEnumType::WON_T_DO,
                            IssueResolutionEnumType::DUPLICATE,
                            IssueResolutionEnumType::INCOMPLETE,
                            IssueResolutionEnumType::CANNOT_REPRODUCE,
                            IssueResolutionEnumType::DONE,
                            IssueResolutionEnumType::WON_T_FIX,
                        ],
                        [
                            $this->translator->trans('app.issue.resolutions.fixed'),
                            $this->translator->trans('app.issue.resolutions.won_t_do'),
                            $this->translator->trans('app.issue.resolutions.duplicate'),
                            $this->translator->trans('app.issue.resolutions.incomplete'),
                            $this->translator->trans('app.issue.resolutions.cannot_reproduce'),
                            $this->translator->trans('app.issue.resolutions.done'),
                            $this->translator->trans('app.issue.resolutions.won_t_fix'),
                        ]
                    ),
                    'required'    => false,
                    'label'       => $this->translator->trans('app.issue.resolution'),
                ]
            );
    }

    /**
     * @param FormInterface $form
     *
     * @return FormInterface
     */
    protected function addStatusField(FormInterface $form)
    {
        return $form
            ->add(
                'status',
                'choice',
                [
                    'choice_list' => new ChoiceList(
                        [
                            IssueStatusEnumType::OPEN,
                            IssueStatusEnumType::IN_PROGRESS,
                            IssueStatusEnumType::CLOSED,
                        ],
                        [
                            $this->translator->trans('app.issue.statuses.open'),
                            $this->translator->trans('app.issue.statuses.in_progress'),
                            $this->translator->trans('app.issue.statuses.closed'),
                        ]
                    ),
                    'required'    => true,
                    'label'       => $this->translator->trans('app.issue.status'),
                ]
            );
    }

    /**
     * @param Issue $issue
     *
     * @return bool
     */
    protected function isIssueTypeChangeable(Issue $issue)
    {
        return
            !in_array(
                $issue->getType(),
                [IssueTypeEnumType::STORY, IssueTypeEnumType::SUB_TASK]
            ) ||
            (($issue->getType() === IssueTypeEnumType::STORY) && $issue->getChildren()->isEmpty());
    }
}
