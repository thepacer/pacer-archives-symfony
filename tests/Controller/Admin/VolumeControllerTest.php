<?php

namespace App\Tests\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VolumeControllerTest extends WebTestCase
{
    public function testRequiresLogin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/volume');
        $this->assertResponseRedirects('/login', 302);
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/volume');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Volumes - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Volumes');
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/volume/1');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Volume 1 (1928-1929) - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Volume 1 (1928-1929)');
    }

    public function testEdit(): void
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@example.com');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/admin/volume/1/edit');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleSame('Edit Volume 1 (1928-1929) - [Admin] The Pacer');
        $this->assertSelectorTextContains('span.h4', 'Edit Volume 1 (1928-1929)');
    }
}
