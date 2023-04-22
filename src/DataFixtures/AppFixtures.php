<?php

namespace App\DataFixtures;

use App\Contract\Service\CategoryServiceInterface;
use App\Contract\Service\ForumServiceInterface;
use App\Contract\Service\UserServiceInterface;
use App\Dto\User\UserCreateFixturesDto;
use App\Entity\Category;
use App\Entity\Forum;
use App\Enum\RoleEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly CategoryServiceInterface $categoryService,
        private readonly ForumServiceInterface $forumService
    ) {
        $this->faker = Factory::create('fr');
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws NonUniqueResultException
     */
    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $categories = $this->createCategories();
        $forums = $this->createForums($categories);
    }

    public function createUsers(ObjectManager $manager): void
    {
        $users = [
            new UserCreateFixturesDto(
                'louise',
                'louise@truc.com',
                'bidule',
                RoleEnum::ROLE_ADMIN,
                'louise',
                'soulier',
                new DateTimeImmutable('1990-05-29'),
                'Strasbourg'
            )
        ];

        foreach ($users as $user) {
            $this->userService->createNewUser($user, false);
        }
        $manager->flush();
    }

    /**
     * @return Collection<Category>
     * @throws NonUniqueResultException
     */
    public function createCategories(): Collection
    {
        $categories = new ArrayCollection();
        $titles = [
            'Cinema',
            'Manga',
            'Jeux vidéo',
            'Séries',
            'Dév'
        ];

        foreach ($titles as $title) {
            $entity = new Category();
            $entity->setTitle($title);
            $this->categoryService->createNewCategory($entity);
            $categories->add($entity);
        }
        return $categories;
    }

    /**
     * @param Collection<Category> $categories
     * @return Collection<Forum>
     */
    public function createForums(Collection $categories): Collection
    {
        $forums = new ArrayCollection();
        $titles = [
            ['Star wars', 'Le seigneur des anneaux'],
            ['L\'attaque des titans', 'Naruto', 'One Piece'],
            ['7 days to die', 'Minecraft', 'League of legends'],
            ['The Mandalorian', 'The Last of Us'],
            ['Web', 'C++', 'Python']
        ];
        foreach ($titles as $index => $el) {
            $category = $categories->get($index);
            foreach ($el as $title) {
                $forum = new Forum();
                $forum->setTitle($title)
                    ->setCategory($category)
                    ->setSubTitle($this->faker->sentence);
                $this->forumService->createNewForum($forum);
                $forums->add($forum);
            }
        }
        return $forums;
    }
}
