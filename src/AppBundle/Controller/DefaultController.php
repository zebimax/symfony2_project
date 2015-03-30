<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_home")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template("default/index.html.twig")
     */
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $service = $this->get('app.services.user');

        return [
            'issues' => $service->getUserIssues($user->getId()),
            'activities' => $service->getUserActivities($user->getId()),
        ];
    }
}
