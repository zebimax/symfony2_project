<?php

namespace AppBundle\Tests\Unit\Form\Type;

use AppBundle\Form\Type\ProjectType;

class ProjectTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ProjectType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new ProjectType(
            $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock()
        );
    }

    /**
     * @covers AppBundle\Form\Type\ProjectType::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_project', $this->object->getName());
    }

    /**
     * @covers AppBundle\Form\Type\ProjectType::buildForm
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
                'label',
                'text',
                [
                    'required' => true,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(1))->method('add')
            ->willReturn($builder)
            ->with(
                'code',
                'text',
                [
                    'required' => true,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(2))->method('add')
            ->willReturn($builder)
            ->with(
                'summary',
                'textarea',
                [
                    'required' => false,
                    'label' => null,
                ]
            );
        $this->object->buildForm($builder, $options);
    }
}
