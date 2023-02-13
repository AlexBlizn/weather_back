<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $user->setEmail('johndoe@test.com');
         $user->setPassword('$2y$13$AkZ.Palb1Ba8dRGTkvo5/.VpqPy.GHZb0.n0Ofw8dxSU43eZu/X7q');
         $user->setRoles([]);
         $manager->persist($user);

        $manager->flush();
    }
}
