<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Users extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            ['email' => 'carlos.sgude@gmail.com', 'password' => 'password'],
        ];

        foreach ($data as $datum) {
            $user = new User();
            $user
                ->setEmail($datum['email'])
                ->setPassword($this->encoder->encodePassword($user,$datum['password']))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 0;
    }
}
