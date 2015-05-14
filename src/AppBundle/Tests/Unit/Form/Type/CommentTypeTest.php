<?php

namespace AppBundle\Tests\Form\Type;

use AppBundle\Form\Type\CommentType;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{
    /**
     * @var CommentType
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CommentType(
            $this->translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock()
        );
        parent::setUp();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\Form\Type\CommentType::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_comment', $this->object->getName());
    }

    public function testSetDefaultOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));
        $this->object->setDefaultOptions($resolver);
    }

    public function testBuildForm()
    {
        $expectedFields = array(
            'body' => 'textarea',
        );
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $counter = 0;
        foreach ($expectedFields as $fieldName => $formType) {
            $builder->expects($this->at($counter))
                ->method('add')
                ->with($fieldName, $formType)
                ->will($this->returnSelf());
            $counter++;
        }
        $this->object->buildForm($builder, []);
    }
}
