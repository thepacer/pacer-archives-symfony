<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles([
            'ROLE_ADMIN',
        ]);
        $user->setPassword($this->passwordEncoder->hashPassword(
            $user,
            'temporarypassword'
        ));

        $manager->persist($user);

        $manager->flush();
    }
}
