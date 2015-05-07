<?php

namespace AppBundle\Service;

use AppBundle\Entity\Repository\IssueActivities;
use AppBundle\Entity\Repository\Issues;
use AppBundle\Entity\Repository\Projects;
use AppBundle\Entity\Repository\Users;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Translation\TranslatorInterface;

abstract class AbstractControllerService
{
    /** @var EntityManager */
    protected $manager;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * @param EntityManager       $manager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $manager, TranslatorInterface $translator)
    {
        $this->manager    = $manager;
        $this->translator = $translator;
    }

    /**
     * @return Projects
     */
    protected function getProjectsRepository()
    {
        return $this->manager->getRepository('AppBundle:Project');
    }

    /**
     * @return Issues
     */
    protected function getIssuesRepository()
    {
        return $this->manager->getRepository('AppBundle:Issue');
    }

    /**
     * @return IssueActivities
     */
    protected function getActivitiesRepository()
    {
        return $this->manager->getRepository('AppBundle:IssueActivity');
    }

    /**
     * @return Users
     */
    protected function getUsersRepository()
    {
        return $this->manager->getRepository('AppBundle:User');
    }
}
