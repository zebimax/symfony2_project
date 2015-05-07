<?php

namespace AppBundle\Entity\Repository;

use AppBundle\DBAL\IssueStatusEnumType;
use AppBundle\Entity\Issue;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Issues extends EntityRepository
{
    /**
     * get issues where user is collaborator
     *
     * @param int $userId
     *
     * @return Issue[]
     */
    public function getNotClosedUserIssues($userId)
    {
        $queryBuilder = $this->getNotClosedIssuesQueryBuilder();
        return $queryBuilder
            ->join('i.collaborators', 'u')
            ->andWhere($queryBuilder->expr()->eq('u.id', ':userId'))
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * get issues where user is assignee
     *
     * @param int $userId
     *
     * @return Issue[]
     */
    public function getNotClosedUserAssignedIssues($userId)
    {
        $queryBuilder = $this->getNotClosedIssuesQueryBuilder();
        return $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('i.assignee', ':userId'))
            ->getQuery()
            ->setParameter('userId', $userId)
            ->getResult();
    }

    /**
     * @param int $projectId
     *
     * @return Issue[]
     */
    public function getProjectIssues($projectId)
    {
        $queryBuilder = $this->createQueryBuilder('i');
        return $queryBuilder
            ->select(['i'])
            ->join('i.project', 'p')
            ->where($queryBuilder->expr()->eq('i.project', ':projectId'))
            ->setParameters(['projectId' => $projectId])
            ->getQuery()
            ->getResult();
    }

    /**
     * get issues of projects where user is a member
     *
     * @param int $userId
     *
     * @return Issue[]
     */
    public function getUserProjectsIssues($userId)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $userProjectsSubQuery = $queryBuilder
            ->from('AppBundle:Project', 'p1')
            ->select('p1.id')
            ->join('p1.users', 'u1')
            ->where($queryBuilder->expr()->eq('u1', ':userId'))
            ->getQuery();

        return $this->createQueryBuilder('i')
            ->select(['i'])
            ->join('i.project', 'p')
            ->andWhere($queryBuilder->expr()->exists($userProjectsSubQuery->getDQL()))
            ->orderBy('p.id')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    private function getNotClosedIssuesQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('i');
        return $queryBuilder
            ->select(['i'])
            ->where($queryBuilder->expr()->neq('i.status', ':statusClosed'))
            ->setParameter('statusClosed', IssueStatusEnumType::CLOSED);
    }
}
