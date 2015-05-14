<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $admin       = (new Role())->setName('role.administrator')->setRole(Role::ADMINISTRATOR);
        $roleManager = (new Role())->setName('role.manager')->setRole(Role::MANAGER);
        $operator    = (new Role())->setName('role.operator')->setRole(Role::OPERATOR);

        $manager->persist($admin);
        $manager->persist($roleManager);
        $manager->persist($operator);

        $manager->flush();

        $this->addReference('role_administrator', $admin);
        $this->addReference('role_manager', $roleManager);
        $this->addReference('role_operator', $operator);
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
