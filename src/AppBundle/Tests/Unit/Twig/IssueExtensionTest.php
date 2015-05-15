<?php

namespace AppBundle\Tests\Unit\Twig;

use AppBundle\Twig\IssueExtension;

class IssueExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueExtension
     */
    protected $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $translatorMock;

    protected function setUp()
    {
        $this->translatorMock = $this
            ->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')
            ->getMock();
        $this->translatorMock
            ->expects($this->any())
            ->method('trans')
            ->willReturnArgument(0);
        $this->object = new IssueExtension($this->translatorMock);
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getName
     */
    public function testGetName()
    {
        $this->assertEquals('app_issue_extension', $this->object->getName());
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getFilters
     */
    public function testGetFilters()
    {
        $this->assertEquals(
            [
                new \Twig_SimpleFilter('renderIssueStatus', [$this->object, 'getStatus']),
                new \Twig_SimpleFilter('renderIssueType', [$this->object, 'getType']),
                new \Twig_SimpleFilter('renderIssuePriority', [$this->object, 'getPriority']),
                new \Twig_SimpleFilter('renderIssueResolution', [$this->object, 'getResolution']),
                new \Twig_SimpleFilter('renderShortIssueDescription', [$this->object, 'shortIssueDescription']),
            ],
            $this->object->getFilters()
        );
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getStatus
     */
    public function testGetStatus()
    {
        $this->assertEquals('app.issue.statuses.test_status', $this->object->getStatus('test_status'));
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getType
     */
    public function testGetType()
    {
        $this->assertEquals('app.issue.types.test_type', $this->object->getType('test_type'));
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getPriority
     */
    public function testGetPriority()
    {
        $this->assertEquals('app.issue.priorities.test_priority', $this->object->getPriority('test_priority'));
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::getResolution
     */
    public function testGetResolution()
    {
        $this->assertEquals('app.issue.resolutions.test_resolution', $this->object->getResolution('test_resolution'));
    }

    /**
     * @covers AppBundle\Twig\IssueExtension::shortIssueDescription
     */
    public function testShortIssueDescription()
    {
        $this->assertEquals('l', $this->object->shortIssueDescription('long description', 1));
    }
}
