<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Issue;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IssueController extends Controller
{
    /**
     * @Route("/issue/add", name="app_issue_add")
     */
    public function addAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/issue/list", name="app_issue_list")
     */
    public function listAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/issue/view/{id}", name="app_issue_view")
     * @param Issue $issue
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Issue $issue)
    {
        return $this->render('default/index.html.twig');
    }
}
