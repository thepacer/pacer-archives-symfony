<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{
    public function testRequiresLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/issue');
        $this->assertResponseRedirects('/login', 302);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/issue');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Issues - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Issues');
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/issue/1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('December 17, 1928 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'December 17, 1928');
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/issue/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Edit December 17, 1928 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Edit December 17, 1928');
    }
}
