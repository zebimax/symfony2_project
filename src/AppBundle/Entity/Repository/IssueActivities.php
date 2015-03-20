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
}
