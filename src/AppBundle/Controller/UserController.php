<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user/add", name="app_user_add")
     * @Security("is_granted('users_add')")
     * @Template("user/add.html.twig")
     */
    public function addAction()
    {
        $form = $this->container->get('app.services.user_form')->getAddForm();
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

        return [
            'user' => $user,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/user/list", name="app_user_list")
     * @Security("is_granted('users_list')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Template("user/list.html.twig")
     */
    public function listAction(Request $request)
    {
        $users = $this->get('app.services.user')->getUsersList(
            $request->query->get($this->container->getParameter('app.page_name'), 1),
            $this->container->getParameter('app.services.user.list_limit')
        );
        return ['users' => $users];
    }

    /**
     * @param User $userObject
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/edit/{id}", name="app_user_edit")
     * @Template("user/edit.html.twig")
     * @Security("is_granted('edit', userObject)")
     */
    public function editAction(User $userObject)
    {
        $form = $this->createForm('app_user', $userObject);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash(
                    'flash_user_edit',
                    $this->container->get('translator.default')->trans('app.messages.user_edit_success')
                );
                return $this->redirect($this->generateUrl('app_user_edit', ['id' => $userObject->getId()]));
            }
        }

        return [
            'user' => $userObject,
            'form' => $form->createView()
        ];
    }


    /**
     * @param User $userObject
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/view/{id}", name="app_user_view")
     * @Template("user/view.html.twig")
     * @Security("is_granted('view', userObject)")
     */
    public function viewAction(User $userObject)
    {
        $service = $this->get('app.services.user');
        return [
            'issues' => $service->getUserAssignedIssues($userObject->getId()),
            'activities' => $service->getUserActivities($userObject->getId()),
            'user' => $userObject
        ];
    }
}
