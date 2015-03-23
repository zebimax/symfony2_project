<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class ProjectService extends AbstractControllerService
{
    /** @var PaginatorInterface */
    protected $paginator;

    public function __construct(
        EntityManager $manager,
        PaginatorInterface $paginatorInterface,
        TranslatorInterface $translator
    ) {
        $this->paginator = $paginatorInterface;
        parent::__construct($manager, $translator);
    }

    public function getProjectsList(User $user, $page, $limit)
    {
        $projects = $this->getProjectsRepository();

        $queryBuilder = $user->isManager()
            ? $projects->getAllProjectsQuery()
            : $projects->getUserProjectsQuery($user->getId());

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }

    public function getAllProjectsQuery()
    {
        return $this->getProjectsRepository()->getAllProjectsQuery();
    }

    public function getProjectIssues(Project $project)
    {
        return $this->getIssuesRepository()->getProjectIssues($project->getId());
    }

    public function getProjectActivities(Project $project)
    {
        return $this->getActivitiesRepository()->getProjectActivities($project->getId());
    }

    public function getMembers(Project $project, $page, $limit)
    {
        return $this->paginator->paginate($project->getUsers(), $page, $limit);
    }

    public function removeMember(Project $project, User $user)
    {
        $result = $project->removeUser($user);
        $this->manager->persist($project);
        $this->manager->flush();
        return $result
            ? $this->translator->trans('app.messages.project.remove_member.success')
            : $this->translator->trans('app.messages.project.remove_member.fail');
    }
}
