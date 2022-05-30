<?php

namespace App\DataFixtures;

use App\Entity\Season;
// use App\Repository\ProgramRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    // private ProgramRepository $programRepository;

    // public function __construct(ProgramRepository $programRepository)
    // {
    //     $programRepository->findAll();
    // }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // $programTitles = $this->programRepository->findAll();
        // foreach ($programTitles as $programTitle) { 
        // }

        foreach (CategoryFixtures::CATEGORIES as $category) {
            for ($index=1; $index <= 15; $index++) {
                for ($i = 1; $i <= 5; $i++) {  
                    $season = new Season();  
                    $season->setNumber($i);
                    $season->setYear($faker->year());
                    $season->setDescription($faker->paragraphs(3, true));
                    $season->setProgramId($this->getReference('category_' . $category . '_program_' . $index));
                    $manager->persist($season);
                    $this->addReference('category_' . $category . '_program_' . $index . 'season_' . $i, $season);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont SeasonFixtures dépend
        return [
          ProgramFixtures::class,
        ];
    }
}
