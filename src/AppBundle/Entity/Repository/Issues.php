<?php

namespace AppBundle\Entity\Repository;

use AppBundle\DBAL\IssueStatusEnumType;
use Doctrine\ORM\EntityRepository;

class Issues extends EntityRepository
{
    /**
     * @param $userId
     *
     * @return array
     */
    public function getNotClosedUserIssues($userId)
    {
        $queryBuilder = $this->getUserIssuesQueryBuilder($userId);

        return $queryBuilder
            ->join('i.collaborators', 'u')
            ->andWhere($queryBuilder->expr()->eq('u.id', ':userId'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     *
     * @return array
     */
    public function getNotClosedUserAssignedIssues($userId)
    {
        $queryBuilder = $this->getUserIssuesQueryBuilder($userId);

        return $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('i.assignee', ':userId'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $projectId
     *
     * @return array
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
     * @param $userId
     *
     * @return array
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

        return $this->getUserIssuesQueryBuilder($userId)
            ->andWhere($queryBuilder->expr()->exists($userProjectsSubQuery->getDQL()))
            ->orderBy('p.id')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getUserIssuesQueryBuilder($userId)
    {
        $queryBuilder = $this->createQueryBuilder('i');

        return $queryBuilder
            ->select(['i'])
            ->join('i.project', 'p')
            ->where($queryBuilder->expr()->neq('i.status', ':statusClosed'))
            ->setParameters(['statusClosed' => IssueStatusEnumType::CLOSED, 'userId' => $userId]);
    }
}
