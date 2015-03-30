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
        $this->assertCount(0, $this->entity->getUsers());
    }

    /**
     * @param string $property
     * @param string $value
     * @param string $expected
     * @covers AppBundle\Entity\Project::setLabel
     * @covers AppBundle\Entity\Project::setSummary
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

    public function testPrePersist()
    {
        $this->entity->setLabel('First letter from each word in upper case');
        $this->entity->prePersist();
        $this->assertEquals('FLFEWIUC', $this->entity->getCode());
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
            'summary'    => ['summary', 'test_summary', 'test_summary']
        ];
    }

    /**
     * @covers AppBundle\Entity\Project::removeUser
     */
    public function testRemoveFromCollections()
    {
        $user = new User();
        $this->entity->addUser($user);

        $this->entity->removeUser($user);

        $this->assertEquals(0, count($this->entity->getUsers()));
    }

    /**
     * @covers AppBundle\Entity\Project::addUser
     */
    public function testAddToCollections()
    {
        $this->entity->addUser(new User());
        $this->assertGreaterThan(0, count($this->entity->getUsers()));
    }
}
