<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArchiveControllerTest extends WebTestCase
{
    public function testArchive()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Archives - The Pacer');
        $this->assertSelectorTextContains('h2', 'Archives');
    }

    public function testVolume()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/volume/1');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Volume 1 (1928-1929) - The Pacer');
        $this->assertSelectorTextContains('h2', 'Volume 1 (1928-1929)');
    }

    public function testYear()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/year/1928');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Issues Published in 1928 - The Pacer');
        $this->assertSelectorTextContains('h2', 'Issues Published in 1928');
    }

    public function testIssue()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/issue/1928-12-17');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('The Volette - December 17, 1928 - The Pacer');
        $this->assertSelectorTextContains('h2', 'December 17, 1928');
    }

    public function testArticle()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/article/test-article-1/1');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Test Article 1 - December 17, 1928 - The Pacer');
        $this->assertSelectorTextContains('h2', 'Test Article 1');
    }

    public function testArticleRedirect()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/article/foo-bar/1');
        $this->assertResponseRedirects(
            '/archive/article/test-article-1/1',
            301
        );
    }

    public function testLegacyIssue()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/legacy-issue/1928-12-17');
        $this->assertResponseRedirects(
            '/archive/issue/1928-12-17',
            301
        );
    }

    public function testLegacyArticle()
    {
        $client = static::createClient();
        $client->request('GET', '/archive/legacy-article/100');
        $this->assertResponseRedirects(
            '/archive/article/test-article-1/1',
            301
        );
    }
}
