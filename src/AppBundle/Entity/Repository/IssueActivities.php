<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\IssueActivity;
use Doctrine\ORM\EntityRepository;

class IssueActivities extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @return IssueActivity[]
     */
    public function getUserActivities($userId)
    {
        return $this->createQueryBuilder('a')
            ->select(['a'])
            ->join('a.issue', 'i')
            ->join('i.project', 'p')
            ->join('p.users', 'u')
            ->where('u.id = :userId')
            ->setParameters(['userId' => $userId])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $projectId
     *
     * @return IssueActivity[]
     */
    public function getProjectActivities($projectId)
    {
        return $this->createQueryBuilder('a')
            ->select(['a'])
            ->join('a.issue', 'i')
            ->join('i.project', 'p')
            ->where('p.id = :projectId')
            ->addOrderBy('a.created')
            ->setParameters(['projectId' => $projectId])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $issueId
     *
     * @return IssueActivity[]
     */
    public function getIssueActivities($issueId)
    {
        return $this->createQueryBuilder('a')
            ->select(['a'])
            ->join('a.issue', 'i')
            ->where('i.id = :issueId')
            ->addOrderBy('a.created')
            ->setParameters(['issueId' => $issueId])
            ->getQuery()
            ->getResult();
    }
}
