<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Issue;
use App\Entity\Volume;

class IssueFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $url = 'https://archive.org/advancedsearch.php?' . http_build_query([
            'q' => 'collection:thepacer',
            'fl' => [
                'date',
                'identifier',
                'title',
                'volume',
                'issue',
                'pages'
            ],
            'sort' => [
                'date asc'
            ],
            'rows' => 3000,
            'page' => 1,
            'output' => 'json'
        ]);

        $client = new \GuzzleHttp\Client();
        $result = $client->request('GET', $url);

        $json = json_decode($result->getBody());

        foreach ($json->response->docs as $doc) {
            print $doc->title . PHP_EOL;

            $issue = new Issue();
            $issue->setIssueNumber($doc->issue);
            $issue->setVolume($manager->getRepository(Volume::class)->findOneBy([
                'volumeNumber' => (int) $doc->volume
            ]));
            $issue->setIssueDate(new \DateTime($doc->date));
            $issue->setArchiveKey($doc->identifier);
            $issue->setPageCount(isset($doc->pages) ? $doc->pages : 0);
            $manager->persist($issue);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            VolumeFixtures::class,
        );
    }
}
