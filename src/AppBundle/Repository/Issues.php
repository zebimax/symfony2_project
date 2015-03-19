<?php

namespace AppBundle\Repository;

use AppBundle\Entity\IssueStatus;
use Doctrine\ORM\EntityRepository;

class Issues extends EntityRepository
{
    public function getNotClosedUserIssues($userId)
    {
        return $this->createQueryBuilder('issues')
            ->select([
                'issues.id',
                'issues.summary',
                'issues.description',
                'project.label project_label'
            ])
            ->join('issues.collaborators', 'users')
            ->join('issues.status', 'status')
            ->join('issues.project', 'project')
            ->where('users.id = :userId')
            ->andWhere('status.code != :statusClosed')
            ->setParameters(['userId' => $userId, 'statusClosed' => IssueStatus::CLOSED])
            ->getQuery()
            ->getArrayResult();
    }
}
