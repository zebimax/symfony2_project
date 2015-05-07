<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Projects extends EntityRepository
{
    /**
     * @param int $userId
     *
     * @return QueryBuilder
     */
    public function getUserProjectsQuery($userId)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->join('p.users', 'u')
            ->where('u = :userId')
            ->setParameters(['userId' => $userId]);
    }

    /**
     * @return QueryBuilder
     */
    public function getAllProjectsQuery()
    {
        return $this->createQueryBuilder('p')
            ->select('p');
    }
}
