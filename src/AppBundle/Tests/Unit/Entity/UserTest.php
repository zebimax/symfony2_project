<?php

namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\Issue;
use AppBundle\Entity\Project;
use AppBundle\Entity\Role;
use AppBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var User
     */
    private $entity;

    protected function setUp()
    {
        $this->entity = new User();
    }

    public function testCreate()
    {
        $this->assertEmpty($this->entity->getId());
        $this->assertTrue($this->entity->getIsActive());
        $this->assertEmpty($this->entity->getRoles());
    }

    /**
     * @param string $property
     * @param string $value
     * @param string $expected
     * @dataProvider getSetDataProvider
     * @covers AppBundle\Entity\User::getAvatar
     * @covers AppBundle\Entity\User::setAvatar
     * @covers AppBundle\Entity\User::getEmail
     * @covers AppBundle\Entity\User::setEmail
     * @covers AppBundle\Entity\User::getFullname
     * @covers AppBundle\Entity\User::setFullname
     * @covers AppBundle\Entity\User::getIsActive
     * @covers AppBundle\Entity\User::setIsActive
     * @covers AppBundle\Entity\User::getPassword
     * @covers AppBundle\Entity\User::setPassword
     * @covers AppBundle\Entity\User::getUsername
     * @covers AppBundle\Entity\User::setUsername
     *
     */
    public function testGetSet($property, $value, $expected)
    {
        call_user_func([$this->entity, 'set'.ucfirst($property)], $value);
        $this->assertEquals(
            $expected,
            call_user_func([$this->entity, 'get'.ucfirst($property)])
        );
    }

    /**
     * Data provider.
     *
     * @return array
     */
    public function getSetDataProvider()
    {
        return [
            'avatar' => ['avatar', 'test_avatar', 'test_avatar'],
            'email' => ['email', 'test@mail.com', 'test@mail.com'],
            'fullname' => ['fullname', 'test_fullname', 'test_fullname'],
            'isActive' => ['isActive', false, false],
            'password' => ['password', 'test_password', 'test_password'],
            'username' => ['username', 'test_username', 'test_username'],
        ];
    }

    /**
     * @covers AppBundle\Entity\User::removeProject
     * @covers AppBundle\Entity\User::removeRole
     * @covers AppBundle\Entity\User::removeIssue
     */
    public function testRemoveFromCollections()
    {
        $project = new Project();
        $role = new Role();
        $issue = new Issue();
        $this->entity
            ->addProject($project)
            ->addRole($role)
            ->addIssue($issue);

        $this->entity
            ->removeProject($project)
            ->removeRole($role)
            ->removeIssue($issue);

        $this->assertCount(0, $this->entity->getProjects());
        $this->assertCount(0, $this->entity->getRoles());
        $this->assertCount(0, $this->entity->getIssues());
    }

    /**
     * @param $property
     * @param $addValue
     * @dataProvider addToCollectionsDataProvider
     *
     * @covers AppBundle\Entity\User::addProject
     * @covers AppBundle\Entity\User::addRole
     * @covers AppBundle\Entity\User::addIssue
     */
    public function testAddToCollections($property, $addValue)
    {
        $ucFirstProperty = ucfirst($property);
        call_user_func([$this->entity, 'add'.$ucFirstProperty], $addValue);
        $this->assertGreaterThan(0, count(call_user_func([$this->entity, 'get'.$ucFirstProperty.'s'])));
    }

    /**
     * @covers AppBundle\Entity\User::getPrimaryRole
     */
    public function testGetPrimaryRole()
    {
        $roleOperator = (new Role())->setRole(Role::OPERATOR);
        $roleManager = (new Role())->setRole(Role::MANAGER);
        $roleAdmin = (new Role())->setRole(Role::ADMINISTRATOR);
        $this->entity
            ->addRole($roleOperator)
            ->addRole($roleManager)
            ->addRole($roleAdmin);
        $this->assertEquals(Role::ADMINISTRATOR, $this->entity->getPrimaryRole());

        $this->entity->removeRole($roleAdmin);
        $this->assertEquals(Role::MANAGER, $this->entity->getPrimaryRole());

        $this->entity->removeRole($roleManager);
        $this->assertEquals(Role::OPERATOR, $this->entity->getPrimaryRole());
    }

    /**
     * @covers AppBundle\Entity\User::serialize
     */
    public function testSerialize()
    {
        $this->entity
            ->setUsername('testUsername')
            ->setPassword('testPass');

        $this->assertEquals(
            [
                null,
                'testUsername',
                'testPass'
            ],
            unserialize($this->entity->serialize())
        );
    }

    /**
     * @covers AppBundle\Entity\User::unserialize
     */
    public function testUnserialize()
    {
        $data = [
            1,
            'testUsername',
            'testPass'
        ];
        $this->entity->unserialize(serialize($data));
        $this->assertEquals($data[0], $this->entity->getId());
        $this->assertEquals($data[1], $this->entity->getUsername());
        $this->assertEquals($data[2], $this->entity->getPassword());
    }

    /**
     * @covers AppBundle\Entity\User::getRolesArray
     */
    public function testGetRolesArray()
    {
        $roleOperator = (new Role())->setRole(Role::OPERATOR);
        $roleManager = (new Role())->setRole(Role::MANAGER);
        $roleAdmin = (new Role())->setRole(Role::ADMINISTRATOR);
        $this->entity
            ->addRole($roleOperator)
            ->addRole($roleManager)
            ->addRole($roleAdmin);
        $rolesArray = $this->entity->getRolesArray();

        $this->assertArrayHasKey(Role::OPERATOR, $rolesArray);
        $this->assertArrayHasKey(Role::MANAGER, $rolesArray);
        $this->assertArrayHasKey(Role::ADMINISTRATOR, $rolesArray);
    }

    /**
     * @return array
     */
    public function addToCollectionsDataProvider()
    {
        return [
            'role' => ['role', new Role()],
            'project' => ['project', new Project()],
            'issue' => ['issue', new Issue()],
        ];
    }
}
