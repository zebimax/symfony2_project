<?php

namespace AppBundle\Service;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Translation\TranslatorInterface;

class UserService extends AbstractControllerService
{
    const USERS_LIMIT = 10;

    /** @var PaginatorInterface */
    protected $paginator;

    /**
     * @param EntityManager       $manager
     * @param TranslatorInterface $translator
     * @param PaginatorInterface  $paginatorInterface
     *
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

    /**
     * @param int $page
     * @param int $limit
     *
     * @return PaginationInterface
     */
    public function getUsersList($page, $limit)
    {
        return $this->paginator->paginate(
            $this->getUsersRepository()->getListQuery(),
            $page,
            $limit
        );
    }

    /**
     * @param int $userId
     *
     * @return Issue[]
     */
    public function getUserIssues($userId)
    {
        return $this->getIssuesRepository()->getNotClosedUserIssues($userId);
    }

    /**
     * @param int $userId
     *
     * @return Issue[]
     */
    public function getUserAssignedIssues($userId)
    {
        return $this->getIssuesRepository()->getNotClosedUserAssignedIssues($userId);
    }

    /**
     * @param int $userId
     *
     * @return IssueActivity[]
     */
    public function getUserActivities($userId)
    {
        return $this->getActivitiesRepository()->getUserActivities($userId);
    }
}
