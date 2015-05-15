<?php

namespace AppBundle\Tests\Unit\Form\Type;

use AppBundle\Form\Type\UserType;

class UserTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new UserType(
            $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock()
        );
    }

    /**
     * @covers AppBundle\Form\Type\UserType::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_user', $this->object->getName());
    }

    /**
     * @covers AppBundle\Form\Type\UserType::buildForm
     */
    public function testBuildForm()
    {
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();
        $builder->expects($this->at(1))->method('add')
            ->willReturn($builder)
            ->with(
                'email',
                'email',
                [
                    'required' => true,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(2))->method('add')
            ->willReturn($builder)
            ->with(
                'username',
                'text',
                [
                    'required' => true,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(3))->method('add')
            ->willReturn($builder)
            ->with(
                'fullname',
                'text',
                [
                    'required' => true,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(4))->method('add')
            ->willReturn($builder)
            ->with(
                'timezone',
                'timezone',
                [
                    'required' => false,
                    'label' => null,
                ]
            );
        $builder->expects($this->at(5))->method('add')
            ->willReturn($builder)
            ->with(
                'file',
                'file',
                [
                    'required' => false,
                    'label' => null,
                ]
            );
        $this->object->buildForm($builder, []);
    }
}
