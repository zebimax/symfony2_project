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
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $user = new User();
        $userFormService = $this->container->get('app.services.user_form');
        $form = $userFormService->getUserForm($user, $currentUser);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                $this->get('app.services.user_password_service')->setUserPassword(
                    $user,
                    $form->get('password')->getData(),
                    $this->container->getParameter('app.user_password_length')
                );
                try {
                    $userFormService->saveUser($user);
                    $message = 'app.messages.user.add.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.user.add.fail';
                }
                $this->addFlash(
                    'flash_user_actions',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_user_list'));
            }
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/user/list", name="app_user_list")
     * @Security("is_granted('users_list')")
     *
     * @param Request $request
     *
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
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/edit/{id}", name="app_user_edit")
     * @Template("user/edit.html.twig")
     * @Security("is_granted('edit', userObject)")
     */
    public function editAction(User $userObject)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $userFormService = $this->container->get('app.services.user_form');
        $form = $userFormService->getUserForm($userObject, $currentUser);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                try {
                    $userFormService->saveUser($userObject);
                    $message = 'app.messages.user.update.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.user.update.fail';
                }
                $this->addFlash(
                    'flash_user_actions',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_user_view', ['id' => $userObject->getId()]));
            }
        }

        return [
            'user' => $userObject,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param User $userObject
     *
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
            'user' => $userObject,
        ];
    }
}
