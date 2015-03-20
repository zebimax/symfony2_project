<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class Users extends EntityRepository
{
    public function getListQuery()
    {
        return $this->createQueryBuilder('u')->select('u');
    }
}
