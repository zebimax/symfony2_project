<?php

namespace AppBundle\Tests\Unit\DBAL;

use AppBundle\DBAL\IssueResolutionEnumType;
use Doctrine\DBAL\Types\Type;

class IssueResolutionEnumTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueResolutionEnumType
     */
    protected $object;

    protected function setUp()
    {
        $this->object = Type::getType(IssueResolutionEnumType::TYPE_NAME);
    }

    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\DBAL\IssueResolutionEnumType::getName
     */
    public function testGetName()
    {
        $this->assertEquals(IssueResolutionEnumType::TYPE_NAME, $this->object->getName());
    }

    /**
     * @covers AppBundle\DBAL\IssueResolutionEnumType::getValues
     */
    public function testGetValues()
    {
        $expected = [
            IssueResolutionEnumType::FIXED,
            IssueResolutionEnumType::WON_T_FIX,
            IssueResolutionEnumType::DUPLICATE,
            IssueResolutionEnumType::INCOMPLETE,
            IssueResolutionEnumType::CANNOT_REPRODUCE,
            IssueResolutionEnumType::DONE,
            IssueResolutionEnumType::WON_T_DO,
            null,
        ];
        $this->assertEquals($expected, $this->object->getValues());
    }
}
