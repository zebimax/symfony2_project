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

    protected function setUp()
    {
        $this->object = new ProjectMemberType();
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
