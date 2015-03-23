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
            ->getArrayResult();
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
            ->getArrayResult();
    }

    /**
     * @param $userId
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function getUserIssuesQueryBuilder($userId)
    {
        return $this->createQueryBuilder('i')
            ->select([
                'i.id',
                'i.summary',
                'i.description',
                'p.code project_code',
                'p.id project_id'
            ])
            ->join('i.project', 'p')
            ->where('i.status != :statusClosed')
            ->setParameters(['userId' => $userId, 'statusClosed' => IssueStatusEnumType::CLOSED]);
    }
}
