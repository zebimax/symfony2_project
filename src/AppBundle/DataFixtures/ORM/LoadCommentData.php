<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
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

        $project = (new Comment())
            ->setBody('test comment')
            ->setUser($user)
            ->setIssue($issue);

        $manager->persist($project);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 15;
    }
}
