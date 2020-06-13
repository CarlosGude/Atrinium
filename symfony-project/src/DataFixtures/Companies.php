<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class Companies extends Fixture implements OrderedFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        foreach ($manager->getRepository(Sector::class)->findAll() as $sector) {
            $company = new Company();
            $company
                ->setName('Comapny '.$sector->getName())
                ->setEmail('info@'.strtolower(str_replace(' ', '-', $company->getName())).'com')
                ->setPhone(random_int(600000000, 699999999))
                ->setSector($sector)
            ;

            $manager->persist($company);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 2;
    }
}
