<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 24; $i++) {  
            $episode = new Episode();  
            $episode->setTitle('Épisode n°' . $i);
            $episode->setNumber($i);
            $episode->setSynopsis('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Aliquam vestibulum morbi blandit cursus risus at ultrices. Bibendum at varius vel pharetra vel turpis nunc eget.');
            $episode->setSeasonId($this->getReference('season_1'));
            $manager->persist($episode);  
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
