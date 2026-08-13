<?php

namespace App\DataFixtures;

use App\Contract\Service\CategoryServiceInterface;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixture extends Fixture
{
    private const TITLES = [
        'Cinema',
        'Manga',
        'Jeux vidéo',
        'Séries',
        'Dév'
    ];

    public function __construct(
        private readonly CategoryServiceInterface $categoryService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::TITLES as $title) {
            $entity = new Category();
            $entity->setTitle($title);
            $this->categoryService->createNewCategory($entity);
        }
    }
}
