<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArchiveControllerTest extends WebTestCase
{
    public function testArchive()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testVolume()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/volume/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testYear()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/year/1928');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIssue()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/issue/1928-12-17');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testArticle()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/article/test-article-1/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testArticleRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/article/foo-bar/1');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testLegacyIssue()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/legacy-issue/1928-12-17');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }

    public function testLegacyArticle()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/legacy-article/100');
        $this->assertEquals(301, $client->getResponse()->getStatusCode());
    }
}
