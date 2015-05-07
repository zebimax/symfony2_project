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
     */
    public function testAddToCollections($property, $addValue)
    {
        $ucFirstProperty = ucfirst($property);
        call_user_func([$this->entity, 'add'.$ucFirstProperty], $addValue);
        $this->assertGreaterThan(0, count(call_user_func([$this->entity, 'get'.$ucFirstProperty.'s'])));
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
