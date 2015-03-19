<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="app_user_add")
     * @Security("is_granted('users_add')")
     */
    public function addAction()
    {
        $user = new User();
        $form = $this->createForm('app_user', $user)->add(
            'password',
            'text',
            [
                'required' => false,
                'label' => $this->container->get('translator.default')->trans('app.password_will_be_generated')
            ]
        );

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                $plainPassword = $form->get('password')->getData();
                if (!$plainPassword) {
                    $generator = $this->container->get('hackzilla.password_generator.computer');
                    $plainPassword = $generator->setLength(12)->generatePassword();
                }

                $encodePassword = $this->container->get('security.password_encoder')
                    ->encodePassword($user, $plainPassword);
                $user->setPassword($encodePassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('app_user_list'));
            }
        }

        return $this->render('user/add.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/list", name="app_user_list")
     * @Security("is_granted('users_list')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $queryBuilder = $this->getDoctrine()
            ->getEntityManager()
            ->createQueryBuilder()
            ->from('AppBundle:User', 'u')
            ->select('u');
        $paginator  = $this->get('knp_paginator');
        $users = $paginator->paginate(
            $queryBuilder,
            $request->query->get('page', 1),
            10
        );
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
        $form = $this->createForm('app_user', $user);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'flash_user_edit',
                    $this->container->get('translator.default')->trans('app.messages.user_edit_success')
                );
                return $this->redirect($this->generateUrl('app_user_edit', ['id' => $user->getId()]));
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
