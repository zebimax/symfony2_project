<?php

namespace AppBundle\Tests\Unit\DBAL;

use AppBundle\DBAL\IssueTypeEnumType;
use Doctrine\DBAL\Types\Type;

class IssueTypeEnumTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueTypeEnumType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = Type::getType(IssueTypeEnumType::TYPE_NAME);
    }

    /**
     * @covers AppBundle\DBAL\IssueTypeEnumType::getName
     */
    public function testGetName()
    {
        $this->assertEquals(IssueTypeEnumType::TYPE_NAME, $this->object->getName());
    }

    /**
     * @covers AppBundle\DBAL\IssueTypeEnumType::getValues
     */
    public function testGetValues()
    {
        $expected = [
            IssueTypeEnumType::BUG,
            IssueTypeEnumType::SUB_TASK,
            IssueTypeEnumType::TASK,
            IssueTypeEnumType::STORY
        ];
        $this->assertEquals($expected, $this->object->getValues());
    }
}
