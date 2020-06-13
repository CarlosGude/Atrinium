<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class Users extends Fixture implements OrderedFixtureInterface
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $data = [
            ['email' => 'admin@atrinium.wip', 'password' => 'password', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'user@atrinium.wip', 'password' => 'password'],
        ];

        foreach ($data as $datum) {
            $user = new User();
            $user
                ->setEmail($datum['email'])
                ->setPassword($datum['password'])
            ;

            /** @var Sector $sector */
            foreach ($this->getSectors($manager) as $sector) {
                $user->addAuthorizedSector($sector);
            }

            if (isset($datum['roles'])) {
                $user->setRoles($datum['roles']);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 3;
    }

    /**
     * @throws Exception
     */
    protected function getSectors(ObjectManager $manager): array
    {
        $sectors = $manager->getRepository(Sector::class)->findAll();
        shuffle($sectors);
        $array = array_chunk($sectors, random_int(1, count($sectors)));

        return array_pop($array);
    }
}
