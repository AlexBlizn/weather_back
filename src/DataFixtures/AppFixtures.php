<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $city = new City();
        $city->setName(name: 'Riga');
        $city->setUnits(units: 'm');
        $city->setCountry(country: 'Latvia');
        $manager->persist($city);

        $manager->flush();
    }
}
