<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm('app_login');

        $formView = $form->createView();
        return $this->render(
            'form/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
                'form'          => $formView
            ]
        );
    }
}
