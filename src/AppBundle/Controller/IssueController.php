<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\IssueTypeEnumType;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IssueController extends Controller
{
    /**
     * @Route("/issue/list", name="app_issue_list")
     * @Template("issue/list.html.twig")
     * @Security("is_granted('issues_list')")
     *
     * @param Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $issues = $this->get('app.services.issue')->getIssuesList(
            $user,
            $request->query->get($this->container->getParameter('app.page_name'), 1),
            $this->container->getParameter('app.services.issue.list_limit')
        );

        return ['issues' => $issues];
    }

    /**
     * @Route("/issue/view/{id}", name="app_issue_view")
     * @Template("issue/view.html.twig")
     *
     * @param Issue $issue
     * @Security("is_granted('view', issue)")
     *
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        $issueService = $this->get('app.services.issue');

        return [
            'issue' => $issue,
            'activities' => $issueService->getIssueActivities($issue),
        ];
    }

    /**
     * @Route("/issue/{id}/sub_task/add", name="app_issue_add_sub_task")
     * @Template("issue/add_sub_task.html.twig")
     * @Security("is_granted('add_sub_task', issue)")
     *
     * @param Issue $issue
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addSubTaskAction(Issue $issue)
    {
        $translator = $this->get('translator.default');
        if ($issue->getType() !== IssueTypeEnumType::STORY) {
            $this->addFlash(
                'flash_issue_actions',
                $translator->trans('app.errors.issue.not_story_add_sub_task_error')
            );
            $this->redirect($this->generateUrl('app_issue_view').['id' => $issue->getId()]);
        }
        $issueFormService = $this->container->get('app.services.issue_form');
        $subTask = new Issue();
        /** @var User $user */
        $user = $this->getUser();
        $form = $issueFormService->getIssueForm($subTask, $user, $issue);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                try {
                    $issueFormService->createIssue($subTask, $issue->getProject(), $user);
                    $message = 'app.messages.project.add_issue.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.project.add_issue.fail';
                }
                $this->addFlash(
                    'flash_issue_actions',
                    $translator->trans($message)
                );

                return $this->redirect($this->generateUrl('app_issue_view', ['id' => $subTask->getId()]));
            }
        }

        return [
            'project' => $issue->getProject(),
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Issue $issue
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/issue/edit/{id}", name="app_issue_edit")
     * @Template("issue/edit.html.twig")
     * @Security("is_granted('edit', issue)")
     */
    public function editAction(Issue $issue)
    {
        /** @var User $user */
        $user = $this->getUser();
        $issueFormService = $this->container->get('app.services.issue_form');
        $form = $issueFormService->getIssueForm($issue, $user);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $issueFormService->saveIssue($issue);
                    $message = 'app.messages.issue.edit.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.issue.edit.fail';
                }
                $this->addFlash(
                    'flash_issue_actions',
                    $this->get('translator.default')->trans($message)
                );

                return $this->redirect($this->generateUrl('app_issue_view', ['id' => $issue->getId()]));
            }
        }

        return [
            'issue' => $issue,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/issue/{id}/comment/add", name="app_issue_add_comment")
     * @Template("issue/add_comment.html.twig")
     * @Security("is_granted('add_comment', issue)")
     *
     * @param Issue $issue
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addCommentAction(Issue $issue)
    {
        $redirectUrl = $this->get('request')->headers->get('referer');
        if (!$redirectUrl) {
            $redirectUrl = $this->generateUrl('app_home');
        }
        /** @var User $user */
        $user = $this->getUser();
        $comment = new Comment();

        $commentService = $this->get('app.services.comment');
        $form = $commentService->getCommentForm($comment, $issue, $user);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $commentService->createComment($comment, $issue, $user);
                    $message = 'app.messages.issue.add_comment.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.issue.add_comment.fail';
                }
                $this->addFlash(
                    'flash_issue_actions',
                    $this->get('translator.default')->trans($message)
                );
            }
        }

        return $this->redirect($redirectUrl);
    }
}
