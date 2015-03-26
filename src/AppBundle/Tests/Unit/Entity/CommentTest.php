<?php
namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueActivity;
use AppBundle\Entity\User;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-03-24 at 19:15:19.
 */
class CommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Comment
     */
    protected $object;
    /**
     * @var Issue
     */
    protected $issue;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->issue = new Issue();
        $this->object = new Comment($this->issue, new User());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers AppBundle\Entity\Comment::setBody
     */
    public function testSetBody()
    {
        $this->assertNull($this->object->getBody());
        $this->object->setBody('body');
        $this->assertEquals('body', $this->object->getBody());
    }

    /**
     * @covers AppBundle\Entity\Comment::getBody
     */
    public function testGetBody()
    {
        $this->object->setBody('body');
        $this->assertEquals('body', $this->object->getBody());
    }

    /**
     * @covers AppBundle\Entity\Comment::getActivity
     */
    public function testGetActivity()
    {
        $this->assertNull($this->object->getActivity());
    }

    /**
     * @covers AppBundle\Entity\Comment::getIssue
     */
    public function testGetIssue()
    {
        $this->assertEquals($this->issue, $this->object->getIssue());
    }

    /**
     * @covers AppBundle\Entity\Comment::prePersist
     */
    public function testPrePersist()
    {
        $this->object->prePersist();
        $this->assertEquals(IssueActivity::COMMENT_ISSUE, $this->object->getActivity()->getType());
        $this->assertEquals($this->object->getUser(), $this->object->getActivity()->getUser());
        $this->assertGreaterThan(0, count($this->object->getIssue()->getComments()));
    }
}