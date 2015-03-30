<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;

class CommentControllerTest extends WebTestCase
{
    public function testEdit()
    {
        /** @var Comment $comment */
        $comment = $this->getReference('test_comment');
        $id = $comment->getId();
        $crawler = $this->client->request('GET', '/comment/edit/'.$id);
        $body = 'new comment body';
        $form = $crawler->selectButton('Edit')->form(['app_comment[body]' => $body]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $selector = sprintf('html:contains("%s")', $body);
        $this->assertTrue($crawler->filter($selector)->count() > 0);
    }

    public function testRemove()
    {
        /** @var Issue $story */
        $story = $this->getReference('issue_story');
        $issueId = $story->getId();
        $crawler = $this->client->request('GET', '/issue/view/'.$issueId);
        /** @var Comment $comment */
        $comment = $this->getReference('test_comment');
        $id = $comment->getId();
        $link = $crawler
            ->filter(sprintf('a[href="/comment/remove/%d"]', $id))
            ->selectLink('remove comment')->link();
        $this->client->followRedirects();
        $crawler = $this->client->click($link);
        $this->assertTrue($crawler->filter('html:contains("comment removed")')->count() > 0);
    }

    protected function setFixtures()
    {
        $this->fixtures = [
            'AppBundle\DataFixtures\ORM\LoadRoleData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadProjectData',
            'AppBundle\DataFixtures\ORM\LoadIssueData',
            'AppBundle\DataFixtures\ORM\LoadCommentData',
        ];
    }
}
