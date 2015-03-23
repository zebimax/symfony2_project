<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $project = (new Project())->setLabel('test_project');
        /** @var User $userOperator */
        $userOperator = $this->getReference('user_operator');
        $project->addUser($userOperator);
        $manager->persist($project);

        $manager->flush();

        $this->addReference('test_project', $project);
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
