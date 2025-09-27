<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $article = $this->getReference(ArticleFixtures::ARTICLE_1, \App\Entity\Article::class);

        $image = new Image();
        $image->setArticle($article);
        $image->setCaption('A photo caption.');
        $image->setCredit('Peter Parker');
        $image->setPath('photo_upload/1928-12-17/photo.jpg');
        $manager->persist($image);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
