<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Volume;

class VolumeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Adds 100 Years of Volumes
        $v = 0;
        do {
            $start_year = $v + 1928;
            $end_year = $start_year + 1;

            $volume = new Volume();
            $volume->setVolumeNumber($v + 1);
            $volume->setVolumeStartDate(new \DateTime("$start_year-08-01"));
            $volume->setVolumeEndDate(new \DateTime("$end_year-07-31"));
            $manager->persist($volume);
            $v++;
        } while ($v <= 99);

        $manager->flush();
    }
}
