<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    /**
     * @Template("issue_comments.html.twig")
     * @Security("is_granted('comments_list', issue)")
     * @param Issue $issue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function listAction(Issue $issue)
    {
        /** @var User $user */
        $user = $this->getUser();
        $comment = new Comment();
        $form = $this->get('app.services.comment')->getCommentForm($comment, $issue, $user);

        return [
            'issue' => $issue,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/comment/edit/{id}", name="app_comment_edit")
     * @Template("comment/edit.html.twig")
     * @Security("is_granted('edit', comment)")
     * @param Comment $comment
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Comment $comment)
    {
        $commentService = $this->get('app.services.comment');
        $form = $commentService->getCommentForm($comment);

        if ($this->get('request')->getMethod() === 'POST') {
            $form->submit($this->get('request'));
            if ($form->isValid()) {
                try {
                    $commentService->saveComment($comment);
                    $message = 'app.messages.comment.update.success';
                } catch (\Exception $e) {
                    $message = 'app.messages.comment.update.fail';
                }
                $this->addFlash(
                    'flash_issue_actions',
                    $this->get('translator.default')->trans($message)
                );
                $this->redirect($this->generateUrl('app_issue_view', ['id' => $comment->getIssue()->getId()]));
            }
        }

        return [
            'issue' => $comment->getIssue(),
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/comment/remove/{id}", name="app_comment_remove")
     * @param Comment $comment
     * @Security("is_granted('remove', comment)")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Comment $comment)
    {
        $commentService = $this->get('app.services.comment');
        try {
            $commentService->removeComment($comment);
            $message = 'app.messages.comment.remove.success';
        } catch (\Exception $e) {
            $message = 'app.messages.comment.remove.fail';
        }
        $this->addFlash(
            'flash_issue_actions',
            $this->get('translator.default')->trans($message)
        );
        return $this->redirect($this->generateUrl('app_issue_view', ['id' => $comment->getIssue()->getId()]));
    }
}
