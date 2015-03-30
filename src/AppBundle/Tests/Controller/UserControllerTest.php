<?php

namespace AppBundle\Tests\Controller;


use AppBundle\Entity\Role;
use AppBundle\Entity\User;

class UserControllerTest extends WebTestCase
{
    public function testAdd()
    {
        /** @var Role $roleOperator */
        $roleOperator = $this->getReference('role_operator');
        $userName = 'new_user';
        $email = 'new_user@mail.com';
        $crawler = $this->client->request('GET', '/user/add');
        $form = $crawler->selectButton('Add user')->form(
            [
                'app_user[username]' => $userName,
                'app_user[email]' => $email
            ]
        );
        $form['app_user[roles]']->select([$roleOperator->getId()]);
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("")', $userName))->count() > 0
        );
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("")', $email))->count() > 0
        );
    }

    public function testList()
    {
        /** @var User $admin */
        $admin = $this->getReference('user_administrator');

        /** @var User $manager */
        $manager = $this->getReference('user_manager');

        /** @var User $operator */
        $operator = $this->getReference('user_operator');

        $crawler = $this->client->request('GET', '/user/list');
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/user/edit/%d"]', $admin->getId()))->count() > 0
        );
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/user/edit/%d"]', $manager->getId()))->count() > 0
        );
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/user/edit/%d"]', $operator->getId()))->count() > 0
        );
    }

    public function testEdit()
    {
        /** @var User $operator */
        $operator = $this->getReference('user_operator');
        $fullName = 'Edited full name';
        $crawler = $this->client->request('GET', '/user/edit/' . $operator->getId());
        $form = $crawler->selectButton('Save user')->form(
            [
                'app_user[fullname]' => $fullName
            ]
        );
        $this->client->followRedirects();
        $crawler = $this->client->submit($form);
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $fullName), $fullName)->count() > 0
        );
    }

    public function testView()
    {
        /** @var User $operator */
        $operator = $this->getReference('user_operator');
        $crawler = $this->client->request('GET', sprintf('/user/view/%d', $operator->getId()));
        $this->assertTrue(
            $crawler->filter(sprintf('html:contains("%s")', $operator->getEmail()))->count() > 0
        );
        $this->assertTrue(
            $crawler->filter(sprintf('a[href="/user/edit/%d"]', $operator->getId()))->count() > 0
        );
    }

    protected function setFixtures()
    {
        $this->fixtures = [
            'AppBundle\DataFixtures\ORM\LoadRoleData',
            'AppBundle\DataFixtures\ORM\LoadUserData'
        ];
    }
}
