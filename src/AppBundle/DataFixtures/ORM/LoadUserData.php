<?php
namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Role;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractOrderedContainerAwareFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);

        $admin->setUsername('admin')
            ->setFullname('admin')
            ->setEmail('admin@mail.com')
            ->setPassword($encoder->encodePassword('admin', null))
            ->addRole($this->getRoleReference('role_administrator'));

        $userManager = (new User())->setUsername('manager')
            ->setFullname('manager')
            ->setEmail('manager@mail.com')
            ->setPassword($encoder->encodePassword('manager', null))
            ->addRole($this->getRoleReference('role_manager'));

        $operator = (new User())->setUsername('operator')
            ->setFullname('operator')
            ->setEmail('operator@mail.com')
            ->setPassword($encoder->encodePassword('operator', null))
            ->addRole($this->getRoleReference('role_operator'));

        $manager->persist($admin);
        $manager->persist($operator);
        $manager->persist($userManager);

        $manager->flush();

        $this->addReference('user_administrator', $admin);
        $this->addReference('user_manager', $userManager);
        $this->addReference('user_operator', $operator);
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

    /**
     * @param $name
     * @return Role
     */
    protected function getRoleReference($name)
    {
        return $this->getReference($name);
    }
}
