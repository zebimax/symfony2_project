<?php

namespace AppBundle\Entity\Repository;

use AppBundle\DBAL\IssueStatusEnumType;
use Doctrine\ORM\EntityRepository;

class Issues extends EntityRepository
{
    /**
     * @param $userId
     * @return array
     */
    public function getNotClosedUserIssues($userId)
    {
        return $this->getUserIssuesQueryBuilder($userId)
            ->join('i.collaborators', 'u')
            ->andWhere('u.id = :userId')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     * @return array
     */
    public function getNotClosedUserAssignedIssues($userId)
    {
        return $this->getUserIssuesQueryBuilder($userId)
            ->andWhere('i.assignee = :userId')
            ->getQuery()
            ->getResult();
    }

    public function getProjectIssues($projectId)
    {
        return $this->createQueryBuilder('i')
            ->select(['i'])
            ->join('i.project', 'p')
            ->where('i.project = :projectId')
            ->setParameters(['projectId' => $projectId])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $userId
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getUserIssuesQueryBuilder($userId)
    {
        return $this->createQueryBuilder('i')
            ->select(['i'])
            ->join('i.project', 'p')
            ->where('i.status != :statusClosed')
            ->setParameters(['userId' => $userId, 'statusClosed' => IssueStatusEnumType::CLOSED]);
    }
}
