<?php

namespace AppBundle\Service;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use AppBundle\EventListener\Event\IssueActivityEvent;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareInterface;
use AppBundle\EventListener\EventDispatcher\EventDispatcherAwareTrait;
use AppBundle\Service\Form\AbstractFormService;

class CommentService extends AbstractFormService implements EventDispatcherAwareInterface
{
    use EventDispatcherAwareTrait;

    /**
     * @param Comment $comment
     * @param Issue   $issue
     * @param User    $user
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCommentForm(Comment $comment, Issue $issue = null, User $user = null)
    {
        $builder = $this->factory->createBuilder('app_comment', $comment);
        if ($user !== null && $comment->getUser() === null) {
            $comment->setUser($user);
        }
        if ($issue !== null && $comment->getIssue() === null) {
            $comment->setIssue($issue);
        }

        return $builder->getForm();
    }

    /**
     * @param Comment $comment
     * @param Issue   $issue
     * @param User    $user
     */
    public function createComment(Comment $comment, Issue $issue, User $user)
    {
        $comment->setUser($user)->setIssue($issue);

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
