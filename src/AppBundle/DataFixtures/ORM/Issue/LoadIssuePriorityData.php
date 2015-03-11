<?php

namespace AppBundle\DataFixtures\ORM\Issue;

use AppBundle\DataFixtures\ORM\AbstractOrderedContainerAwareFixture;
use AppBundle\Entity\IssuePriority;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssuePriorityData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $trivial = (new IssuePriority())
            ->setCode(IssuePriority::TRIVIAL)
            ->setName(IssuePriority::TRIVIAL);

        $minor = (new IssuePriority())
            ->setCode(IssuePriority::MINOR)
            ->setName(IssuePriority::MINOR);

        $major = (new IssuePriority())
            ->setCode(IssuePriority::MAJOR)
            ->setName(IssuePriority::MAJOR);

        $blocker = (new IssuePriority())
            ->setCode(IssuePriority::BLOCKER)
            ->setName(IssuePriority::BLOCKER);

        $manager->persist($trivial);
        $manager->persist($minor);
        $manager->persist($major);
        $manager->persist($blocker);

        $manager->flush();

        $this->addReference('issue_priority_trivial', $trivial);
        $this->addReference('issue_priority_minor', $minor);
        $this->addReference('issue_priority_major', $major);
        $this->addReference('issue_priority_blocker', $blocker);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
