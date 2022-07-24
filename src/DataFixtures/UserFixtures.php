<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $user=new User();
        $user->setUsername('demo');
        $user->setFullName('demo');
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);
        $user->setEmail('sjftheone@gmail.com');
        $user->setPassword($this->encoder->hashPassword($user,'demo'));

        // $product = new Product();
         $manager->persist($user);

        $manager->flush();
    }
}
