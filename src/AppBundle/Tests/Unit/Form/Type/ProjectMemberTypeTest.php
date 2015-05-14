<?php

namespace AppBundle\Tests\Unit\Form\Type;

use AppBundle\Form\Type\ProjectMemberType;
use Symfony\Component\Validator\Constraints\Choice;

class ProjectMemberTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectMemberType
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ProjectMemberType();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\Form\Type\ProjectMemberType::buildForm
     */
    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $options['data'] = [1 => 'test'];
        $builder->expects($this->at(0))->method('add')
            ->willReturn($builder)
            ->with(
                'users',
                'choice',
                [
                    'choices' => $options['data'],
                    'required' => true,
                    'attr' => array('class' => 'form-control'),
                    'constraints' => new Choice(['choices' => array_keys($options['data'])]),
                ]
            );
        $this->object->buildForm($builder, $options);
    }

    /**
     * @covers AppBundle\Form\Type\ProjectMemberType::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_project_member', $this->object->getName());
    }
}
