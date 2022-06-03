<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testRequiresLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/image');
        $this->assertResponseRedirects('/login', 302);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/image');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Images - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Images');
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/image/1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Image #1 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Image #1');
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/image/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Edit Image #1 - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Edit Image #1');
    }
}
