<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class Users extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function getListQuery()
    {
        return $this->createQueryBuilder('u')->select('u');
    }

    /**
     * @param int $projectId
     *
     * @return User[]
     */
    public function getNotProjectUsers($projectId)
    {
        $currentProjectUsersSubQuery = $this->createQueryBuilder('u_sub')
            ->select('u_sub.id')
            ->join('u_sub.projects', 'p')
            ->where('p.id = :projectId')
            ->getQuery();

        return $this->createQueryBuilder('u')
            ->select(['u.id', 'u.username'])
            ->where("u.id NOT IN ({$currentProjectUsersSubQuery->getDQL()})")
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getArrayResult();
    }
}
