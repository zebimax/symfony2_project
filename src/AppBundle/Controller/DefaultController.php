<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\IssueActivities;
use AppBundle\Repository\Issues;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_home")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render(
            'default/index.html.twig',
            [
                'issues' => $this->getUserIssues($user->getId()),
                'activities' => $this->getUserActivities($user->getId())
            ]
        );
    }

    /**
     * @param $userId
     * @return array
     */
    protected function getUserIssues($userId)
    {
        return $this->getIssuesRepository()->getNotClosedUserIssues($userId);
    }

    /**
     * @return Issues
     */
    protected function getIssuesRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:Issue');
    }

    /**
     * @param $userId
     * @return array
     */
    protected function getUserActivities($userId)
    {
        return $this->getActivitiesRepository()->getUserActivities($userId);
    }

    /**
     * @return IssueActivities
     */
    protected function getActivitiesRepository()
    {
        return $this->getDoctrine()->getRepository('AppBundle:IssueActivity');
    }
}
