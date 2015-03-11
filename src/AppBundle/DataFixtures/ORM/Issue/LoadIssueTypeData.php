<?php

namespace AppBundle\DataFixtures\ORM\Issue;

use AppBundle\DataFixtures\ORM\AbstractOrderedContainerAwareFixture;
use AppBundle\Entity\IssueType;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssueTypeData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $bug = (new IssueType())->setCode(IssueType::BUG)->setName(IssueType::BUG);
        $task = (new IssueType())->setCode(IssueType::TASK)->setName(IssueType::TASK);
        $subTask = (new IssueType())->setCode(IssueType::SUB_TASK)->setName(IssueType::SUB_TASK);
        $story = (new IssueType())->setCode(IssueType::STORY)->setName(IssueType::STORY);

        $manager->persist($bug);
        $manager->persist($task);
        $manager->persist($subTask);
        $manager->persist($story);

        $manager->flush();

        $this->addReference('issue_type_bug', $bug);
        $this->addReference('issue_type_task', $task);
        $this->addReference('issue_type_sub_task', $subTask);
        $this->addReference('issue_type_story', $story);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
