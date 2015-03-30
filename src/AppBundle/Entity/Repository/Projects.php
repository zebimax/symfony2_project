<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Projects extends EntityRepository
{
    /**
     * @param $userId
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getAllProjectsQuery()
    {
        return $this->createQueryBuilder('p')
            ->select('p');
    }
}
