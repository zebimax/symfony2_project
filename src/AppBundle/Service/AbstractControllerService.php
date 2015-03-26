<?php

namespace AppBundle\Service;

use AppBundle\Entity\Repository\IssueActivities;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

class AbstractControllerService
{
    /** @var EntityManager */
    protected $manager;

    /** @var TranslatorInterface */
    protected $translator;

    /**
     * @param EntityManager $manager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $manager, TranslatorInterface $translator)
    {
        $this->manager = $manager;
        $this->translator = $translator;
    }

    /**
     * @return \AppBundle\Entity\Repository\Projects
     */
    protected function getProjectsRepository()
    {
        return $this->manager->getRepository('AppBundle:Project');
    }

    /**
     * @return \AppBundle\Entity\Repository\Issues
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
     * @return \AppBundle\Entity\Repository\Users
     */
    protected function getUsersRepository()
    {
        return $this->manager->getRepository('AppBundle:User');
    }
}
