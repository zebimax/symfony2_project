<?php

namespace AppBundle\Tests\Unit\DBAL;

use AppBundle\DBAL\IssuePriorityEnumType;
use Doctrine\DBAL\Types\Type;

class IssuePriorityEnumTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssuePriorityEnumType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = Type::getType(IssuePriorityEnumType::TYPE_NAME);
    }

    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\DBAL\IssuePriorityEnumType::getName
     */
    public function testGetName()
    {
        $this->assertEquals(IssuePriorityEnumType::TYPE_NAME, $this->object->getName());
    }

    /**
     * @covers AppBundle\DBAL\IssuePriorityEnumType::getValues
     */
    public function testGetValues()
    {
        $expected = [
            IssuePriorityEnumType::TRIVIAL,
            IssuePriorityEnumType::MINOR,
            IssuePriorityEnumType::MAJOR,
            IssuePriorityEnumType::BLOCKER
        ];
        $this->assertEquals($expected, $this->object->getValues());
    }
}
