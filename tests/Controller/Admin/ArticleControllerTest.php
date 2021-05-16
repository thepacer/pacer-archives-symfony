<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testRequiresLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/article');

        $this->assertResponseRedirects('/login', 302);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/article');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Articles - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Articles');
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/article/1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('12/17/1928: Test Article 1 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', '12/17/1928: Test Article 1');
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/article/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Edit 12/17/1928: Test Article 1 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Edit 12/17/1928: Test Article 1');
    }
}
