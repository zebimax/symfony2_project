<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="app_user_add")
     * @Security("is_granted('users_add')")
     */
    public function addAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/user/list", name="app_user_list")
     * @Security("is_granted('users_list')")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    /**
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/edit/{id}", name="app_user_edit")
     */
    public function editAction(User $user)
    {
        if (!$this->container->get('security.authorization_checker')->isGranted('edit', $user)) {
            throw new AccessDeniedException();
        }
        return $this->render('default/index.html.twig');
    }
}
