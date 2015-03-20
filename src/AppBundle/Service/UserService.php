<?php

namespace AppBundle\Service;

use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\Repository\IssueActivities;
use AppBundle\Entity\Repository\Issues;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;

class UserService
{
    const USERS_LIMIT = 10;

    /** @var EntityManager */
    protected $manager;

    /** @var PaginatorInterface */
    protected $paginator;

    /**
     * @param EntityManager $manager
     * @param PaginatorInterface $paginationInterface
     */
    public function __construct(EntityManager $manager, PaginatorInterface $paginationInterface)
    {
        $this->manager = $manager;
        $this->paginator = $paginationInterface;
    }

    public function getUsersList($page, $limit)
    {
        return $this->paginator->paginate(
            $this->getUsersRepository()->getListQuery(),
            $page,
            $limit
        );
    }

    /**
     * @param $userId
     * @return array
     */
    public function getUserIssues($userId)
    {
        return $this->getIssuesRepository()->getNotClosedUserIssues($userId);
    }

    /**
     * @param $userId
     * @return array
     */
    public function getUserAssignedIssues($userId)
    {
        return $this->getIssuesRepository()->getNotClosedUserAssignedIssues($userId);
    }

    /**
     * @param $userId
     * @return array
     */
    public function getUserActivities($userId)
    {
        return $this->getActivitiesRepository()->getUserActivities($userId);
    }

    /**
     * @return Issues
     */
    protected function getIssuesRepository()
    {
        return $this->manager->getRepository('AppBundle:Issue');
    }

    /**
     * @return IssueActivities
     */
    protected function getActivitiesRepository()
    {
        return $this->manager->getRepository('AppBundle:IssueActivity');
    }

    protected function getUsersRepository()
    {
        return $this->manager->getRepository('AppBundle:User');
    }
}
