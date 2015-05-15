<?php

namespace AppBundle\Tests\Unit\DBAL;

use AppBundle\DBAL\IssueStatusEnumType;
use Doctrine\DBAL\Types\Type;

class IssueStatusEnumTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueStatusEnumType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = Type::getType(IssueStatusEnumType::TYPE_NAME);
    }

    /**
     * @covers AppBundle\DBAL\IssueStatusEnumType::getName
     */
    public function testGetName()
    {
        $this->assertEquals(IssueStatusEnumType::TYPE_NAME, $this->object->getName());
    }

    /**
     * @covers AppBundle\DBAL\IssueStatusEnumType::getValues
     */
    public function testGetValues()
    {
        $expected = [
            IssueStatusEnumType::OPEN,
            IssueStatusEnumType::IN_PROGRESS,
            IssueStatusEnumType::CLOSED
        ];
        $this->assertEquals($expected, $this->object->getValues());
    }
}
