<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProjectData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $project = (new Project())
            ->setLabel('test_project')
            ->setCode('test');

        /** @var User $userOperator */
        $userOperator = $this->getReference('user_operator');
        /** @var User $userAdmin */
        $userAdmin = $this->getReference('user_administrator');

        $project
            ->addUser($userOperator)
            ->addUser($userAdmin);

        $manager->persist($project);
        $manager->flush();

        $this->addReference('test_project', $project);
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
