<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class AbstractControllerService
{
    /** @var EntityManager */
    protected $manager;

    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }
}
