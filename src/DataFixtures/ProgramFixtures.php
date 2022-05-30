<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        foreach (CategoryFixtures::CATEGORIES as $category) {
            for ($i=1; $i <= 15; $i++) { 
                $program = new Program();
                $program->setTitle($faker->lexify('??????????'));
                $program->setSynopsis($faker->paragraph(2));
                $program->setPoster('https://picsum.photos/'. rand(200, 225).'/300');
                $program->setYear($faker->year());
                $program->setCountry($faker->word());
                $program->setCategory($this->getReference('category_' . $category));
                $manager->persist($program);
                $this->addReference('category_' . $category . '_program_' . $i, $program);
            }
        }
        $manager->flush();
        
    // const ACTION_PROGRAMS = [
    //     'La Symfony du Nouveau Monde',
    //     'Symfony en mi bémol',
    //     'Symfony en mie de pain',
    //     'Symfony',
    //     'Symfonin the rain',
    // ];
    //     foreach (self::ACTION_PROGRAMS as $key => $programTitle) {
    //         $program = new Program();
    //         $program->setTitle($programTitle);
    //         $program->setSynopsis('Synopsis ' . $key);
    //         $program->setPoster('https://picsum.photos/'. rand(200, 225).'/300');
    //         $program->setCategory($this->getReference('category_Action'));
    //         $manager->persist($program);
    //         $this->addReference('program_' . $programTitle, $program);
    //     }
    //     $manager->flush();

    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
