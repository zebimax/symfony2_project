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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class IssueController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/issue/list", name="app_issue_list")
     * @Template
     * @Security("is_granted('issues_list')")
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
     * @param Issue $issue
     *
     * @Route("/issue/view/{id}", name="app_issue_view")
     * @Template
     * @Security("is_granted('view', issue)")
     *
     * @return array
     */
    public function viewAction(Issue $issue)
    {
        $issueService = $this->get('app.services.issue');

        return [
            'issue'      => $issue,
            'activities' => $issueService->getIssueActivities($issue),
        ];
    }

    /**
     * @param Issue $issue
     *
     * @Route("/issue/{id}/sub_task/add", name="app_issue_add_sub_task")
     * @Template
     * @Security("is_granted('add_sub_task', issue)")
     *
     * @return array|RedirectResponse
     */
    public function addSubTaskAction(Issue $issue)
    {
        $translator = $this->get('translator.default');
        if ($issue->getType() !== IssueTypeEnumType::STORY) {
            $this->addFlash(
                'flash_issue_actions',
                $translator->trans('app.errors.issue.not_story_add_sub_task_error')
            );
            $this->redirect($this->generateUrl('app_issue_view') . ['id' => $issue->getId()]);
        }
        $subTask = new Issue();
        $subTask->setProject($issue->getProject());
        /** @var User $user */
        $user = $this->getUser();
        $subTask->setType(IssueTypeEnumType::SUB_TASK)->setParent($issue);

        $issueFormService = $this->container->get('app.services.issue_form');
        $form             = $issueFormService->getIssueForm($subTask, $user);
        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                try {
                    $issueFormService->addIssue($subTask, $user);
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
            'issue' => $issue,
            'form'  => $form->createView(),
        ];
    }

    /**
     * @param Issue $issue
     *
     * @Route("/issue/edit/{id}", name="app_issue_edit")
     * @Template
     * @Security("is_granted('edit', issue)")
     *
     * @return array|RedirectResponse
     */
    public function editAction(Issue $issue)
    {
        /** @var User $user */
        $user             = $this->getUser();
        $issueFormService = $this->container->get('app.services.issue_form');
        $form             = $issueFormService->getIssueForm($issue, $user);

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
            'form'  => $form->createView(),
        ];
    }

    /**
     * @param Issue $issue
     *
     * @Route("/issue/{id}/comment/add", name="app_issue_add_comment")
     * @Security("is_granted('add_comment', issue)")
     *
     * @return array|RedirectResponse
     */
    public function addCommentAction(Issue $issue)
    {
        $redirectUrl = $this->get('request')->headers->get('referer');
        if (!$redirectUrl) {
            $redirectUrl = $this->generateUrl('app_home');
        }
        /** @var User $user */
        $user    = $this->getUser();
        $comment = new Comment();
        $comment->setUser($user)->setIssue($issue);
        $commentService = $this->get('app.services.comment');
        $form           = $commentService->getCommentForm($comment);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $commentService->addComment($comment);
                    $message = 'app.messages.issue.add_comment.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.issue.add_comment.fail';
                }
            } else {
                $message = $form->getErrors(true);
            }

            $this->addFlash(
                'flash_issue_actions',
                $this->get('translator.default')->trans($message)
            );
        }

        return $this->redirect($redirectUrl);
    }

    /**
     * @param Request $request
     *
     * @Route("/issue/add", name="app_issue_add")
     * @Template
     * @Security("is_granted('issues_add')")
     *
     * @return array|RedirectResponse
     */
    public function addAction(Request $request)
    {
        $issueFormService = $this->container->get('app.services.issue_form');
        $issue            = new Issue();
        /** @var User $user */
        $user = $this->getUser();
        $form = $issueFormService->getIssueForm($issue, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            try {
                $issueFormService->addIssue($issue, $user);
                $message     = 'app.messages.project.add_issue.success';
                $generateUrl = $this->generateUrl('app_issue_view', ['id' => $issue->getId()]);
            } catch (\Exception $e) {
                $message     = 'app.messages.project.add_issue.fail';
                $generateUrl = $this->generateUrl('app_issue_add');
            }
            $this->addFlash(
                'flash_issue_actions',
                $this->get('translator.default')->trans($message)
            );

            return $this->redirect($generateUrl);
        }

        return [
            'issue' => $issue,
            'form'  => $form->createView(),
        ];
    }
}
