<?php

namespace AppBundle\Service;

use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Translation\TranslatorInterface;

class IssueService extends AbstractService
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
     * @param int  $page
     * @param int  $limit
     *
     * @return PaginationInterface
     */
    public function getIssuesList(User $user, $page, $limit)
    {
        $issues = $this->getIssuesRepository();

        $queryBuilder = $user->isManager()
            ? $issues->findAll()
            : $issues->getUserProjectsIssues($user->getId());

        return $this->paginator->paginate($queryBuilder, $page, $limit);
    }

    /**
     * @param Issue $issue
     *
     * @return IssueActivity[]
     */
    public function getIssueActivities(Issue $issue)
    {
        return $this->getActivitiesRepository()->getIssueActivities($issue->getId());
    }
}
