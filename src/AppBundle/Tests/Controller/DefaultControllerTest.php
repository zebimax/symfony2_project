<?php

namespace AppBundle\Tests\Controller;

class DefaultControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('form[name="app_login"]')->count() > 0);
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertTrue($crawler->filter('html:contains("System Dashboard")')->count() > 0);
        $this->assertTrue($crawler->filter('a[href="/logout"]')->count() > 0);
    }
}
