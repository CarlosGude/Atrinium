<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class Sectors extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager):void
    {
        for ($i=1;$i<100;$i++) {
            $sector = new Sector();
            $sector->setName('Sector '.$i);

            $manager->persist($sector);
        }

        $manager->flush();
    }

    public function getOrder():int
    {
        return 1;
    }
}
