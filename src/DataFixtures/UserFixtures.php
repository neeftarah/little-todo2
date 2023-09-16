<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class UserFixtures extends Fixture
{
    private const USERS = [
        [
            'username' => 'admin',
            'password' => 'admin',
            'email' => 'jmoreau.dev@gmail.com',
        ],
        [
            'username' => 'tester',
            'password' => 'tester',
            'email' => 'tester@app-test.com',
        ],
    ];

    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $userDetails) {
            $user = (new User())
                ->setUsername($userDetails['username'])
                ->setPassword($this->passwordHasher
                                   ->getPasswordHasher(User::class)
                                   ->hash($userDetails['password']))
                ->setEmail($userDetails['email'])
                ->setRoles(['ROLE_USER'])
                ->setIsVerified(true);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
