<?php

namespace AppBundle\Service;

use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\Repository\IssueActivities;
use AppBundle\Entity\Repository\Issues;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class UserService extends AbstractControllerService
{
    const USERS_LIMIT = 10;

    /** @var PaginatorInterface */
    protected $paginator;

    /**
     * @param EntityManager $manager
     * @param TranslatorInterface $translator
     * @param PaginatorInterface $paginatorInterface
     * @internal param PaginatorInterface $paginationInterface
     */
    public function __construct(
        EntityManager $manager,
        TranslatorInterface $translator,
        PaginatorInterface $paginatorInterface
    ) {
        $this->paginator = $paginatorInterface;
        parent::__construct($manager, $translator);
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
}
