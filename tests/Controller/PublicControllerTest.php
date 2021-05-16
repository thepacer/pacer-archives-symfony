<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('The Pacer');
    }

    public function testMissingIssues()
    {
        $client = static::createClient();
        $client->request('GET', '/missing-issues');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Missing Issues - The Pacer');
        $this->assertSelectorTextContains('h2', 'Missing Issues');
    }

    public function testAbout()
    {
        $client = static::createClient();
        $client->request('GET', '/about');
        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('About - The Pacer');
        $this->assertSelectorTextContains('h2', 'Our History');
    }

    public function testDonate()
    {
        $client = static::createClient();
        $client->request('GET', '/donate');
        $this->assertResponseRedirects(
            'https://securelb.imodules.com/s/1341/utaa/form/interior_form.aspx?sid=1341&gid=5&pgid=4197&cid=6250&dids=2802&bledit=1',
            302
        );
    }
}
