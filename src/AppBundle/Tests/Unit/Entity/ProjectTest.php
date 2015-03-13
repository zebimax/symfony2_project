<?php

namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;

class ProjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Project
     */
    private $entity;

    protected function setUp()
    {
        $this->entity = new Project();
    }

    public function testCreate()
    {
        $this->assertEmpty($this->entity->getUsers());
    }

    /**
     * @param string $property
     * @param string $value
     * @param string $expected
     * @dataProvider getSetDataProvider
     */
    public function testGetSet($property, $value, $expected)
    {
        call_user_func([$this->entity, 'set' . ucfirst($property)], $value);
        $this->assertEquals(
            $expected,
            call_user_func([$this->entity, 'get' . ucfirst($property)])
        );
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function getSetDataProvider()
    {
        return [
            'label' => ['label', 'test_label', 'test_label'],
            'code'    => ['code', 'test_code', 'test_code']
        ];
    }

    public function testRemoveFromCollections()
    {
        $user = new User();
        $this->entity->addUser($user);

        $this->entity->removeUser($user);

        $this->assertEquals(0, count($this->entity->getUsers()));
    }

    public function testAddToCollections()
    {
        $this->entity->addUser(new User());
        $this->assertGreaterThan(0, count($this->entity->getUsers()));
    }
}
