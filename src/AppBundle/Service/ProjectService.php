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

    /**
     * @param EntityManager       $manager
     * @param PaginatorInterface  $paginatorInterface
     * @param TranslatorInterface $translator
     */
    public function __construct(
        EntityManager $manager,
        PaginatorInterface $paginatorInterface,
        TranslatorInterface $translator
    ) {
        $this->paginator = $paginatorInterface;
        parent::__construct($manager, $translator);
    }

    /**
     * @param User $user
     * @param $page
     * @param $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getProjectsList(User $user, $page, $limit)
    {
        $projects = $this->getProjectsRepository();

        $queryBuilder = $user->isManager()
            ? $projects->getAllProjectsQuery()
            : $projects->getUserProjectsQuery($user->getId());

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllProjectsQuery()
    {
        return $this->getProjectsRepository()->getAllProjectsQuery();
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getProjectIssues(Project $project)
    {
        return $this->getIssuesRepository()->getProjectIssues($project->getId());
    }

    /**
     * @param Project $project
     *
     * @return array
     */
    public function getProjectActivities(Project $project)
    {
        return $this->getActivitiesRepository()->getProjectActivities($project->getId());
    }

    /**
     * @param Project $project
     * @param $page
     * @param $limit
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function getMembers(Project $project, $page, $limit)
    {
        return $this->paginator->paginate($project->getUsers(), $page, $limit);
    }

    /**
     * @param Project $project
     * @param User    $user
     */
    public function removeMember(Project $project, User $user)
    {
        $project->removeUser($user);
        $this->manager->persist($project);
        $this->manager->flush();
    }
}
