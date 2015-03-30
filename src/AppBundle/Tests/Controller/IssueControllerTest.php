<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Issue;
use Symfony\Component\DomCrawler\Crawler;

class IssueControllerTest extends WebTestCase
{
    public function testList()
    {
        $summaries = [
            'Test bug summary',
            'Test story summary',
            'Test sub_task summary'
        ];
        $crawler = $this->client->request('GET', '/issue/list');
        $filtered = $crawler->filter('td.issue_summary')
            ->each(function (Crawler $node) {
            return $node->text();
        });

        $this->assertSame($summaries, $filtered);
    }

    public function testView()
    {
        /** @var Issue $story */
        $story = $this->getReference('issue_story');
        $id = $story->getId();
        $crawler = $this->client->request('GET', '/issue/view/' . $id);
        $linkFilter = sprintf('a[href="/issue/edit/%d"]', $id);
        $this->assertSame(1, $crawler->filter($linkFilter)->count());
    }

    public function testAddSubTask()
    {
        /** @var Issue $story */
        $story = $this->getReference('issue_story');
        $id = $story->getId();
        $crawler = $this->client->request('GET', '/issue/' . $id . '/sub_task/add');
        $summary = 'test summary(sub task of story)';
        $form = $crawler->selectButton('Add')->form(['app_issue[summary]' => $summary]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $summary))->count() > 0
        );
    }

    public function testEdit()
    {
        /** @var Issue $story */
        $story = $this->getReference('issue_story');
        $id = $story->getId();
        $crawler = $this->client->request('GET', '/issue/edit/' . $id);
        $summary = 'Edited summary';
        $form = $crawler->selectButton('Edit')->form(['app_issue[summary]' => $summary]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $summary))->count() > 0
        );
    }

    public function testAddComment()
    {
        /** @var Issue $story */
        $story = $this->getReference('issue_story');
        $id = $story->getId();
        $crawler = $this->client->request('GET', '/issue/view/' . $id);
        $comment = 'test comment of story';
        $form = $crawler->selectButton('Comment')->form(['app_comment[body]' => $comment]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $comment))->count() > 0
        );
    }

    protected function setFixtures()
    {
        $this->fixtures = [
            'AppBundle\DataFixtures\ORM\LoadRoleData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadProjectData',
            'AppBundle\DataFixtures\ORM\LoadIssueData'
        ];
    }
}