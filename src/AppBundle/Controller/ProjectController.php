<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/project/add", name="app_project_add")
     * @Template
     * @Security("is_granted('projects_add')")
     *
     * @return array|RedirectResponse
     */
    public function addAction()
    {
        $projectFormsService = $this->get('app.services.project_forms');
        $project = new Project();
        $form = $projectFormsService->getProjectForm($project);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $projectFormsService->saveProject($project);
                    $message = 'app.messages.project.add.success';
                    $generateUrl = $this->generateUrl('app_project_view', ['id' => $project->getId()]);
                } catch (\Exception $e) {
                    $message = 'app.messages.project.add.fail';
                    $generateUrl = $this->generateUrl('app_project_add');
                }
                $this->addFlash(
                    'flash_project',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($generateUrl);
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/project/list", name="app_project_list")
     * @Template
     * @Security("is_granted('projects_list')")
     *
     * @param Request $request
     *
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
     * @param Project $project
     *
     * @Route("/project/view/{id}", name="app_project_view")
     * @Template
     * @Security("is_granted('view', project)")
     *
     * @return array
     */
    public function viewAction(Project $project)
    {
        $projectService = $this->get('app.services.project');

        return [
            'project' => $project,
            'issues' => $projectService->getProjectIssues($project),
            'activities' => $projectService->getProjectActivities($project),
        ];
    }

    /**
     * @param Project $project
     *
     * @Route("/project/edit/{id}", name="app_project_edit")
     * @Template
     * @Security("is_granted('projects_edit')")
     *
     * @return array
     */
    public function editAction(Project $project)
    {
        $projectFormsService = $this->get('app.services.project_forms');
        $form = $projectFormsService->getProjectForm($project);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $projectFormsService->saveProject($project);
                    $message = 'app.messages.project.edit.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.project.edit.fail';
                }
                $this->addFlash(
                    'flash_project',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_project_view', ['id' => $project->getId()]));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Project $project
     * @param Request $request
     *
     * @Route("/project/{id}/members/list", name="app_project_members_list")
     * @Template
     * @Security("is_granted('projects_members_list')")
     *
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
            ),
        ];
    }

    /**
     * @param Project $project
     *
     * @Route("/project/{id}/members/add", name="app_project_add_member")
     * @Security("is_granted('projects_members_add')")
     * @Template
     *
     * @return array|RedirectResponse
     */
    public function addMemberAction(Project $project)
    {
        $projectFormsService = $this->container->get('app.services.project_forms');
        $form = $projectFormsService->getMembersForm($project);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                try {
                    $projectFormsService->addMember($project, $form);
                    $message = 'app.messages.project.add_member.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.project.add_member.fail';
                }
                $this->addFlash(
                    'flash_project_members',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_project_members_list', ['id' => $project->getId()]));
            }
        }

        return [
            'project' => $project,
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Project $project
     * @param User    $user
     *
     * @Route("/project/{id}/members/remove/{user_id}", name="app_project_remove_member")
     * @Security("is_granted('projects_members_remove')")
     * @ParamConverter("user", class="AppBundle:User", options={"id": "user_id"})
     *
     * @return array|RedirectResponse
     */
    public function removeMember(Project $project, User $user)
    {
        try {
            $this->get('app.services.project')->removeMember($project, $user);
            $message = 'app.messages.project.remove_member.success';
        } catch (\Exception $e) {
            $message = 'app.messages.comment.remove.fail';
        }

        $this->addFlash('flash_project_members', $this->get('translator.default')->trans($message));

        return $this->redirect($this->generateUrl('app_project_members_list', ['id' => $project->getId()]));
    }

    /**
     * @param Project $project
     *
     * @Route("/project/{id}/issues/add", name="app_project_add_issue")
     * @Template
     * @Security("is_granted('issue_add', project)")
     *
     * @return array|RedirectResponse
     */
    public function addIssueAction(Project $project)
    {
        $issueFormService = $this->container->get('app.services.issue_form');
        $issue = new Issue();
        /** @var User $user */
        $user = $this->getUser();
        $form = $issueFormService->getIssueForm($issue, $user, $project);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                try {
                    $issueFormService->createIssue($issue, $project, $user);
                    $message = 'app.messages.project.add_issue.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.project.add_issue.fail';
                }
                $this->addFlash(
                    'flash_issue_actions',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_issue_view', ['id' => $issue->getId()]));
            }
        }

        return [
            'project' => $project,
            'form' => $form->createView(),
        ];
    }
}
