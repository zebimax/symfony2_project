<?php

namespace AppBundle\DataFixtures\ORM\Issue;

use AppBundle\DataFixtures\ORM\AbstractOrderedContainerAwareFixture;
use AppBundle\Entity\IssuePriority;
use AppBundle\Entity\IssueStatus;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssueStatusData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $open = (new IssueStatus())
            ->setCode(IssueStatus::OPEN)
            ->setName(IssueStatus::OPEN);

        $closed = (new IssueStatus())
            ->setCode(IssueStatus::CLOSED)
            ->setName(IssueStatus::CLOSED);

        $inProgress = (new IssueStatus())
            ->setCode(IssueStatus::IN_PROGRESS)
            ->setName(IssueStatus::IN_PROGRESS);

        $manager->persist($open);
        $manager->persist($closed);
        $manager->persist($inProgress);

        $manager->flush();

        $this->addReference('issue_status_open', $open);
        $this->addReference('issue_status_closed', $closed);
        $this->addReference('issue_status_in_progress', $inProgress);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
