<?php

namespace AppBundle\Service;

use AppBundle\DBAL\IssueStatusEnumType;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareInterface;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareTrait;
use AppBundle\Service\Form\AbstractFormService;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class IssueFormService extends AbstractFormService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    /**
     * @param Issue $issue
     * @param User  $user
     *
     * @return FormInterface
     */
    public function getIssueForm(Issue $issue, User $user)
    {
        $builder = $this->factory->createBuilder('app_issue', $issue);
        if (null === $project = $issue->getProject()) {
            $this->addProjectField($builder, $user);
            $builder->get('project')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($builder) {
                    /** @var null|Project $project */
                    $project = $this->getProjectsRepository()->find($event->getData());
                    $this->addAssigneeField($event->getForm()->getParent(), $project);
                }
            );
        }
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Issue $issue */
                $issue = $event->getData();
                $this->addAssigneeField($event->getForm(), $issue->getProject());
            }
        );

        $currentStatus = $issue->getStatus();
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($currentStatus, $user) {
                /** @var Issue $issue */
                $issue     = $event->getData();
                $newStatus = $issue->getStatus();
                if (!in_array($currentStatus, [null, $newStatus])) {
                    $this->addChangeStatusActivity(
                        $issue,
                        $user,
                        [
                            'old' => ['status' => $currentStatus],
                            'new' => ['status' => $newStatus],
                        ]
                    );
                }
            }
        );
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                /** @var Issue $issue */
                $issue    = $event->getData();
                $assignee = $issue->getAssignee();
                if ($assignee !== null) {
                    $this->addCollaborator($issue, $assignee);
                }
            }
        );

        return $builder->getForm();
    }

    /**
     * @param         param Issue $issue
     * @param User    $user
     */
    public function addIssue(Issue $issue, User $user)
    {
        $activity = (new IssueActivity($issue, $user))
            ->setType(IssueActivity::CREATE_ISSUE)
            ->setCreated($issue->getCreated());
        $issue
            ->addCollaborator($user)
            ->setReporter($user)
            ->setStatus(IssueStatusEnumType::OPEN)
            ->addActivity($activity);

        $this->saveIssue($issue);

        $this->dispatchActivity($activity);
    }

    /**
     * @param Issue $issue
     */
    public function saveIssue(Issue $issue)
    {
        $this->manager->persist($issue);
        $this->manager->flush();
    }

    /**
     * @param Issue $issue
     * @param User  $user
     * @param array $details
     */
    private function addChangeStatusActivity(Issue $issue, User $user, array $details)
    {
        $activity = (new IssueActivity($issue, $user))
            ->setType(IssueActivity::CHANGE_ISSUE_STATUS)
            ->setDetails($details);
        $issue->addActivity($activity);
        $this->dispatchActivity($activity);
    }

    /**
     * @param Issue $issue
     * @param User  $user
     */
    private function addCollaborator(Issue $issue, User $user)
    {
        $issue->addCollaborator($user);
    }

    /**
     * @param IssueActivity $activity
     */
    private function dispatchActivity(IssueActivity $activity)
    {
        $this->dispatcher->dispatch(
            IssueActivityEvent::ISSUE_ACTIVITY,
            new IssueActivityEvent($activity)
        );
    }

    /**
     * @param FormInterface $builder
     * @param Project       $project
     */
    private function addAssigneeField(FormInterface $builder, Project $project = null)
    {
        $builder->add(
            'assignee',
            'entity',
            [
                'class'    => 'AppBundle:User',
                'property' => 'username',
                'label'    => $this->translator->trans('app.issue.assignee'),
                'choices'  => $project ? $project->getUsers() : [],
                'required' => false,
                'attr'     => ['class' => 'form-control'],
            ]
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param User                 $user
     */
    private function addProjectField(FormBuilderInterface $builder, User $user)
    {
        $builder->add(
            'project',
            'entity',
            [
                'class'       => 'AppBundle:Project',
                'property'    => 'label',
                'label'       => $this->translator->trans('app.project'),
                'choices'     => $user->getProjects(),
                'required'    => true,
                'placeholder' => $this->translator->trans('app.messages.project.select_project'),
                'empty_data'  => null,
                'attr'        => ['class' => 'form-control'],
            ]
        );
    }
}
