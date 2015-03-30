<?php

namespace AppBundle\Service;

use AppBundle\Entity\Issue;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class IssueService extends AbstractControllerService
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
     * @return array
     */
    public function getIssueActivities(Issue $issue)
    {
        return $this->getActivitiesRepository()->getIssueActivities($issue->getId());
    }
}
