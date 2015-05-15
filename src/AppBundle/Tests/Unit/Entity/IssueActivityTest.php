<?php

namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\Issue;
use AppBundle\Entity\User;
use AppBundle\Entity\IssueActivity;
use Symfony\Component\Validator\Constraints\DateTime;

class IssueActivityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IssueActivity
     */
    protected $object;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Issue
     */
    protected $issue;

    protected function setUp()
    {
        $this->issue = new Issue();
        $this->user = new User();
        $this->object = new IssueActivity($this->issue, $this->user);
    }

    /**
     * @param string $property
     * @param string $value
     * @param string $expected
     * @dataProvider getSetDataProvider
     * @covers AppBundle\Entity\IssueActivity::getType
     * @covers AppBundle\Entity\IssueActivity::setType
     * @covers AppBundle\Entity\MappedSuperClass\AbstractIssueEvent::setCreated
     * @covers AppBundle\Entity\MappedSuperClass\AbstractIssueEvent::getCreated
     */
    public function testGetSet($property, $value, $expected)
    {
        call_user_func_array(array($this->object, 'set'.ucfirst($property)), [$value]);
        $this->assertEquals($expected, call_user_func_array([$this->object, 'get'.ucfirst($property)], []));
    }

    /**
     * @covers AppBundle\Entity\IssueActivity::getUser
     * @covers AppBundle\Entity\IssueActivity::getIssue
     */
    public function testGet()
    {
        $issue = new Issue();
        $user = new User();
        $activity = new IssueActivity($issue, $user);
        $this->assertEquals($user, $activity->getUser());
        $this->assertEquals($issue, $activity->getIssue());
    }

    /**
     * get set data provider.
     *
     * @return array
     */
    public function getSetDataProvider()
    {
        $created = new DateTime();

        return [
            'type' => ['type', IssueActivity::CREATE_ISSUE, IssueActivity::CREATE_ISSUE],
            'created' => ['created', $created, $created],
        ];
    }
}
