<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;

class LoadRoleData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $admin = (new Role())->setName(Role::ADMINISTRATOR)->setRole(Role::ADMINISTRATOR);
        $roleManager = (new Role())->setName(Role::MANAGER)->setRole(Role::MANAGER);
        $operator = (new Role())->setName(Role::OPERATOR)->setRole(Role::OPERATOR);

        $manager->persist($admin);
        $manager->persist($roleManager);
        $manager->persist($operator);

        $manager->flush();

        $this->addReference('role_administrator', $admin);
        $this->addReference('role_manager', $roleManager);
        $this->addReference('role_operator', $operator);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 11;
    }
}
