<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        foreach (CategoryFixtures::CATEGORIES as $category) {
            for ($index=1; $index <= 15; $index++) {
                for ($index2 = 1; $index2 <= 5; $index2++) { 
                    for ($i = 1; $i <= 10; $i++) {  
                        $episode = new Episode();  
                        $episode->setTitle('Épisode n°' . $i);
                        $episode->setNumber($i);
                        $episode->setSynopsis($faker->boolean ? $faker->paragraph : $faker->sentences(2, true));
                        $episode->setSeasonId($this->getReference('category_' . $category . '_program_' . $index . 'season_' . $index2));
                        $manager->persist($episode);  
                    }
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
