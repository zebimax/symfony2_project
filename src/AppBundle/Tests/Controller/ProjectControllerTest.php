<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;

class ProjectControllerTest extends WebTestCase
{
    public function testAdd()
    {
        $crawler = $this->client->request('GET', '/project/add');
        $label = 'new test label';
        $code = 'code';
        $summary = 'test project summary';
        $form = $crawler->selectButton('Add')->form(
            [
                'app_project[label]' => $label,
                'app_project[code]' => $code,
                'app_project[summary]' => $summary,
            ]
        );
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue($crawler->filter(sprintf('html:contains("%s")', $label))->count() > 0);
        $this->assertTrue($crawler->filter(sprintf('html:contains("%s")', strtoupper($code)))->count() > 0);
        $this->assertTrue($crawler->filter(sprintf('html:contains("%s")', $summary))->count() > 0);
    }

    public function testList()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');
        $crawler = $this->client->request('GET', '/project/list');
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/project/view/%d"]', $project->getId()))->count() > 0
        );
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/project/edit/%d"]', $project->getId()))->count() > 0
        );
    }
    public function testView()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');
        $id = $project->getId();
        $crawler = $this->client->request('GET', '/project/view/'.$id);
        $this->assertTrue($crawler->filter(sprintf('a[href="/project/edit/%s"]', $id))->count() > 0);
    }

    public function testMembersList()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');

        /** @var User $operator */
        $operator = $this->getReference('user_operator');
        $crawler = $this->client->request('GET', sprintf('/project/%d/members/list', $project->getId()));
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $operator->getUsername()))->count() > 0
        );
    }

    public function testAddMember()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');

        /** @var User $manager */
        $manager = $this->getReference('user_manager');

        $crawler = $this->client->request('GET', sprintf('/project/%d/members/add', $project->getId()));
        $form = $crawler->selectButton('Add user')->form(['app_project_member[users]' => $manager->getId()]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue($crawler->filter('html:contains("User was added to project")')->count() > 0);
    }

    public function testRemoveMember()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');

        /** @var User $operator */
        $operator = $this->getReference('user_operator');
        $this->client->followRedirects();
        $crawler = $this->client->request(
            'GET',
            sprintf('/project/%d/members/remove/%d', $project->getId(), $operator->getId())
        );
        $this->assertTrue($crawler->filter('html:contains("User was removed from project")')->count() > 0);
    }

    public function testAddIssue()
    {
        /** @var Project $project */
        $project = $this->getReference('test_project');

        $crawler = $this->client->request('GET', sprintf('/project/%d/issues/add', $project->getId()));
        $summary = 'Summary of issue added to project';
        $form = $crawler->selectButton('Add')->form(
            [
                'app_issue[summary]' => $summary,
            ]
        );
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue($crawler->filter('html:contains("'.$summary.'")')->count() > 0);
    }

    protected function setFixtures()
    {
        $this->fixtures = [
            'AppBundle\DataFixtures\ORM\LoadRoleData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
            'AppBundle\DataFixtures\ORM\LoadProjectData',
        ];
    }
}
