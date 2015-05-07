<?php

namespace AppBundle\Tests\Form\Type;

use AppBundle\Form\Type\IssueType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-03-28 at 22:07:38.
 */
class IssueTypeTest extends TypeTestCase
{
    /**
     * @var IssueType
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new IssueType(
            $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock()
        );
        parent::setUp();
    }

    public function testSetDefaultOptions()
    {
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with($this->isType('array'));
        $this->object->setDefaultOptions($resolver);
    }

    public function testGetName()
    {
        $this->assertEquals('app_issue', $this->object->getName());
    }

    public function testBuildForm()
    {
        $expectedFields = array(
            'summary' => 'text',
            'description' => 'textarea',
            'priority' => 'choice',
        );
        $builder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $counter = 2;
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
