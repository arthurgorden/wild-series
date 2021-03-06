<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Actualité',
        'Aventure',
        'Animation',
        'Anime',
        'Comédie',
        'Comédie Musicale',
        'Crime',
        'Documentaire',
        'Drame',
        'Fantastique',
        'Guerre',
        'Historique',
        'Horreur',
        'Indie',
        'Romantique',
        'Science-Fiction',
        'Suspense',
        'Thriller',
        'Western'
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (SELF::CATEGORIES as $key => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference('category_' . $categoryName, $category);
        }
        $manager->flush();
    }
}
