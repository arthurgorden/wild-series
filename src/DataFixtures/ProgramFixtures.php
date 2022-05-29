<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const ACTION_PROGRAMS = [
        'La Symfony du Nouveau Monde',
        'Symfony en mi bémol',
        'Symfony en mie de pain',
        'Symfony',
        'Symfonin the rain',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ACTION_PROGRAMS as $key => $programTitle) {
            $program = new Program();
            $program->setTitle($programTitle);
            $program->setSynopsis('Synopsis ' . $key);
            $program->setPoster('https://picsum.photos/'. rand(200, 225).'/300');
            $program->setCategory($this->getReference('category_Action'));
            $manager->persist($program);
            $this->addReference('program_' . $programTitle, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          CategoryFixtures::class,
        ];
    }
}
