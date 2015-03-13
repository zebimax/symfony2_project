<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/view/{id}", name="app_profile_view")
     */
    public function viewAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/profile/edit/{id}", name="app_profile_edit")
     */
    public function editAction()
    {
        return $this->render('default/index.html.twig');
    }
}
