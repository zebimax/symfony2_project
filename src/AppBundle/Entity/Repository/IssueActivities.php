<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class IssueActivities extends EntityRepository
{
    /**
     * @param $userId
     * @return array
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
     * @param $projectId
     * @return array
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
     * @param $issueId
     * @return array
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
