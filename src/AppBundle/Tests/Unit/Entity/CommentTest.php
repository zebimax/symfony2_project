<?php

namespace AppBundle\Tests\Unit\Entity;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;

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

    protected function setUp()
    {
        $this->issue = new Issue();
        $this->object = new Comment();
        $this->object->setIssue($this->issue);
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
     * @covers AppBundle\Entity\Comment::getIssue
     */
    public function testGetIssue()
    {
        $this->assertEquals($this->issue, $this->object->getIssue());
    }
}
