<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Search - The Pacer');
        $this->assertSelectorExists('form[action="/search/"]');

        $buttonCrawlerNode = $crawler->selectButton('Search');
        $form = $buttonCrawlerNode->form();
        $form['s'] = 'Some text.';
        $form['index'] = 'content';
        $client->submit($form);

        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Search');
        $form = $buttonCrawlerNode->form();
        $form['s'] = 'Kent';
        $form['index'] = 'author';
        $client->submit($form);

        $this->assertResponseIsSuccessful();

    }
}
