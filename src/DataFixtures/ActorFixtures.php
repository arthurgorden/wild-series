<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ActorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        for ($i=1; $i <= 10; $i++) {
            $actor = new Actor();
            $actor->setName($faker->name());
            // for ($index=1; $index <= 3; $i++) {
            //     $randomCategory = array_rand(array_flip(CategoryFixtures::CATEGORIES));
            //     $actor->addProgram($this->getReference('category_' . $randomCategory . '_program_' . rand(1, 15)));
            // }
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
        }
        $manager->flush();
    }
}
