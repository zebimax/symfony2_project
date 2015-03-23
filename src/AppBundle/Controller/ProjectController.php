<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/add", name="app_project_add")
     */
    public function addAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/project/list", name="app_project_list")
     * @Template("project/list.html.twig")
     * @Security("is_granted('projects_list')")
     * @param Request $request
     * @return array
     */
    public function listAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $projects = $this->get('app.services.project')->getProjectsList(
            $user,
            $request->query->get($this->container->getParameter('app.page_name'), 1),
            $this->container->getParameter('app.services.project.list_limit')
        );
        return ['projects' => $projects];
    }

    /**
     * @Route("/project/view/{id}", name="app_project_view")
     * @Template("project/view.html.twig")
     * @param Project $project
     * @Security("is_granted('view', project)")
     * @return array
     */
    public function viewAction(Project $project)
    {
        $projectService = $this->get('app.services.project');

        return [
            'project' => $project,
            'issues' => $projectService->getProjectIssues($project),
            'activities' => $projectService->getProjectActivities($project)
        ];
    }

    /**
     * @Route("/project/edit/{id}", name="app_project_edit")
     * @Template("project/edit.html.twig")
     * @param Project $project
     * @Security("is_granted('projects_edit')")
     * @return array
     */
    public function editAction(Project $project)
    {
        return ['project' => $project];
    }

    /**
     * @Route("/project/{id}/members/list", name="app_project_members_list")
     * @Template("project/members.html.twig")
     * @param Project $project
     * @Security("is_granted('projects_members_list')")
     * @param Request $request
     * @return array
     */
    public function membersListAction(Project $project, Request $request)
    {
        return [
            'project' => $project,
            'users' => $this->get('app.services.project')->getMembers(
                $project,
                $request->query->get($this->container->getParameter('app.page_name'), 1),
                $this->container->getParameter('app.services.project.members_list_limit')
            )
        ];
    }

    /**
     * @Route("/project/{id}/members/add", name="app_project_add_member")
     * @Security("is_granted('projects_members_add')")
     * @Template(":project:add_member.html.twig")
     * @param Project $project
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addMemberAction(Project $project)
    {
        $form = $this->container->get('app.services.project_forms')->getMembersForm($project);
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
            'project' => $project,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/project/{id}/members/remove/{user_id}", name="app_project_remove_member")
     * @Security("is_granted('projects_members_remove')")
     * @ParamConverter("user", class="AppBundle:User", options={"id": "user_id"})
     * @param Project $project
     * @param User $user
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeMember(Project $project, User $user)
    {
        $resultMessage = $this->get('app.services.project')->removeMember($project, $user);
        $this->addFlash('flash_project_member_remove', $resultMessage);
        return $this->redirect($this->generateUrl('app_project_members_list', ['id' => $project->getId()]));
    }
}
