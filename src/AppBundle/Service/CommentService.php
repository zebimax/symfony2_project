<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use AppBundle\Entity\IssueActivity;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareInterface;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareTrait;
use AppBundle\Service\Form\AbstractFormService;

use Symfony\Component\Form\Form;

class CommentService extends AbstractFormService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    /**
     * @param Comment $comment
     *
     * @return Form
     */
    public function getCommentForm(Comment $comment)
    {
        return $this->factory->createBuilder('app_comment', $comment)->getForm();
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $issue         = $comment->getIssue();
        $user          = $comment->getUser();
        $issueActivity = (new IssueActivity($issue, $user))
            ->setType(IssueActivity::COMMENT_ISSUE)
            ->setCreated($comment->getCreated());

        $issue->addActivity($issueActivity)->addCollaborator($user);
        $this->saveComment($comment);

        $this->dispatcher->dispatch(
            IssueActivityEvent::ISSUE_ACTIVITY,
            new IssueActivityEvent($issueActivity)
        );
    }

    /**
     * @param Comment $comment
     */
    public function saveComment(Comment $comment)
    {
        $this->manager->persist($comment);
        $this->manager->flush();
    }

    /**
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->manager->remove($comment);
        $this->manager->flush();
    }
}
