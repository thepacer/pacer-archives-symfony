<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Issue;
use App\Entity\Volume;

class IssueFixtures extends Fixture implements DependentFixtureInterface
{
    const ISSUE_1 = 'Issue 1';

    public function load(ObjectManager $manager)
    {
        $volume = $this->getReference(VolumeFixtures::VOLUME_1);

        $issue = new Issue();
        $issue->setIssueDate(new \DateTime('1928-12-17'));
        $issue->setVolume($volume);
        $issue->setIssueNumber('1');
        $issue->setPageCount(4);
        $issue->setArchiveKey('TheVolette19281217');
        $issue->setArchiveNotes('');
        $manager->persist($issue);
        $manager->flush();

        $this->addReference(self::ISSUE_1, $issue);
    }

    public function getDependencies()
    {
        return array(
            VolumeFixtures::class,
        );
    }
}
