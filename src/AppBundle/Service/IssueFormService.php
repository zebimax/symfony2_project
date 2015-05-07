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
     * @param Issue   $issue
     * @param User    $user
     * @param Project $project
     *
     * @return FormInterface
     */
    public function getIssueForm(Issue $issue, User $user, Project $project)
    {
        $currentStatus = $issue->getStatus();
        $builder = $this->factory->createBuilder('app_issue', $issue);
        $this->addAssigneeField($builder, $project);
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($currentStatus, $user) {
                /** @var Issue $issue */
                $issue = $event->getData();
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
                $issue = $event->getData();
                $assignee = $issue->getAssignee();
                if ($assignee !== null) {
                    $this->addCollaborator($issue, $assignee);
                }
            }
        );

        return $builder->getForm();
    }

    /**
     * @param param Issue $issue
     * @param Project     $project
     * @param User        $user
     */
    public function createIssue(Issue $issue, Project $project, User $user)
    {
        $activity = (new IssueActivity($issue, $user))
            ->setType(IssueActivity::CREATE_ISSUE)
            ->setCreated($issue->getCreated());
        $issue
            ->addCollaborator($user)
            ->setReporter($user)
            ->setProject($project)
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
     * @param FormBuilderInterface $builder
     * @param Project              $project
     */
    private function addAssigneeField(FormBuilderInterface $builder, Project $project)
    {
        $builder->add(
            'assignee',
            'entity',
            [
                'class' => 'AppBundle:User',
                'property' => 'username',
                'label' => $this->translator->trans('app.issue.assignee'),
                'choices' => $project->getUsers(),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ]
        );

    }
}
