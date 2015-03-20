<?php

namespace AppBundle\DataFixtures\ORM\Issue;

use AppBundle\DataFixtures\ORM\AbstractOrderedContainerAwareFixture;
use AppBundle\Entity\IssueResolution;
use Doctrine\Common\Persistence\ObjectManager;

class LoadIssueResolutionData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $cantReproduce = (new IssueResolution())
            ->setCode(IssueResolution::CANNOT_REPRODUCE)
            ->setName(IssueResolution::CANNOT_REPRODUCE);

        $done = (new IssueResolution())
            ->setCode(IssueResolution::DONE)
            ->setName(IssueResolution::DONE);

        $duplicate = (new IssueResolution())
            ->setCode(IssueResolution::DUPLICATE)
            ->setName(IssueResolution::DUPLICATE);

        $fixed = (new IssueResolution())
            ->setCode(IssueResolution::FIXED)
            ->setName(IssueResolution::FIXED);

        $incomplete = (new IssueResolution())
            ->setCode(IssueResolution::INCOMPLETE)
            ->setName(IssueResolution::INCOMPLETE);

        $wontDo = (new IssueResolution())
            ->setCode(IssueResolution::WON_T_DO)
            ->setName(IssueResolution::WON_T_DO);

        $wontFix = (new IssueResolution())
            ->setCode(IssueResolution::WON_T_FIX)
            ->setName(IssueResolution::WON_T_FIX);

        $manager->persist($cantReproduce);
        $manager->persist($done);
        $manager->persist($duplicate);
        $manager->persist($fixed);
        $manager->persist($incomplete);
        $manager->persist($wontDo);
        $manager->persist($wontFix);

        $manager->flush();

        $this->addReference('issue_resolution_cannot_reproduce', $cantReproduce);
        $this->addReference('issue_resolution_done', $done);
        $this->addReference('issue_resolution_duplicate', $duplicate);
        $this->addReference('issue_resolution_fixed', $fixed);
        $this->addReference('issue_resolution_incomplete', $incomplete);
        $this->addReference('issue_resolution_won_t_do', $wontDo);
        $this->addReference('issue_resolution_won_t_fix', $wontFix);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
