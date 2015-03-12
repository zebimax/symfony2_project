<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/issue", name="issue")
     */
    public function issueAction()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/user/add", name="app_user_add")
     */
    public function userAddAction()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/user/add", name="app_user_list")
     */
    public function userListAction()
    {
        return $this->render('base.html.twig');
    }
}
