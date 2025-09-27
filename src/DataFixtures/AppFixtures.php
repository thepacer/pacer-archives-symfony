<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // This fixture doesn't need to create anything itself,
        // it just ensures all dependent fixtures are loaded
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            VolumeFixtures::class,
            IssueFixtures::class,
            ArticleFixtures::class,
            ImageFixtures::class,
        ];
    }
}
