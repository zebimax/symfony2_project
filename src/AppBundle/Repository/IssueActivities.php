<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class IssueActivities extends EntityRepository
{
    public function getUserActivities($userId)
    {
        return $this->createQueryBuilder('a')
            ->select(['a', 'i.id issue_id'])
            ->leftJoin('a.issue', 'i')
            ->getQuery()
            ->getArrayResult();
    }
}
