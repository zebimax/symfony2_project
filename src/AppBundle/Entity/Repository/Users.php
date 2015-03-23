<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Users extends EntityRepository
{
    public function getListQuery()
    {
        return $this->createQueryBuilder('u')->select('u');
    }

    public function getNotProjectUsers($projectId)
    {
        $currentProjectUsersSubQuery = $this->createQueryBuilder('u_sub')
            ->select('u_sub.id')
            ->join('u_sub.projects', 'p')
            ->where('p.id != :projectId')
            ->getQuery();
        return $this->createQueryBuilder('u')
            ->select(['u.id', 'u.username'])
            ->where("u.id NOT IN ({$currentProjectUsersSubQuery->getDQL()})")
            ->setParameter('projectId', $projectId)
            ->getQuery()
            ->getArrayResult();
    }


}
