<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCommentData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @var Issue $issue */
        $issue = $this->getReference('issue_sub_task');
        /** @var User $user */
        $user = $this->getReference('user_manager');

        $comment = (new Comment())
            ->setIssue($issue)
            ->setUser($user)
            ->setBody('test comment');
        $this->addReference('test_comment', $comment);

        $issueActivity = (new IssueActivity($issue, $user))
            ->setType(IssueActivity::COMMENT_ISSUE)
            ->setCreated($comment->getCreated());

        $issue->addActivity($issueActivity)->addCollaborator($user);

        $manager->persist($comment);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}
