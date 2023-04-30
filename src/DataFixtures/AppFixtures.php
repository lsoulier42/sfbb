<?php

namespace App\DataFixtures;

use App\Contract\Service\CategoryServiceInterface;
use App\Contract\Service\ForumServiceInterface;
use App\Contract\Service\MessageServiceInterface;
use App\Contract\Service\UserServiceInterface;
use App\Dto\Topic\PostDto;
use App\Dto\Topic\TopicDto;
use App\Dto\User\UserCreateFixturesDto;
use App\Entity\Category;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Entity\User;
use App\Enum\RoleEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private readonly UserServiceInterface $userService,
        private readonly CategoryServiceInterface $categoryService,
        private readonly ForumServiceInterface $forumService,
        private readonly MessageServiceInterface $messageService
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
        $users = $this->createUsers($manager);
        $categories = $this->createCategories();
        $forums = $this->createForums($categories);
        $topics = $this->createTopics($forums, $users, $manager);
        $this->createPosts($topics, $users, $manager);
    }

    /**
     * @param ObjectManager $manager
     * @return Collection<User>
     * @throws Exception
     */
    public function createUsers(ObjectManager $manager): Collection
    {
        $collection = new ArrayCollection();
        $password = 'bidule';
        $users = [
            ['louise', 'louise@truc.com', RoleEnum::ROLE_ADMIN, 'louise', 'soulier', '1990-05-29', 'Strasbourg'],
            ['jean-marc', 'jean-marc@truc.com', RoleEnum::ROLE_SUPER_MODERATOR, 'jean-marc', 'dupont', '1985-04-01', 'Paris'],
            ['jean-michel', 'jean-michel@truc.com', RoleEnum::ROLE_MODERATOR, 'jean-michel', 'jarr', '1970-12-24', 'Marseille']
        ];
        foreach ($users as $user) {
            $dto = new UserCreateFixturesDto(
                $user[0],
                $user[1],
                $password,
                $user[2],
                $user[3],
                $user[4],
                new DateTimeImmutable($user[5]),
                $user[6]
            );
            $collection->add($this->userService->createNewUser($dto, false));
        }
        for ($i = 0; $i < 10; $i++) {
            $dto = new UserCreateFixturesDto(
                $this->faker->userName,
                $this->faker->email,
                $password,
                RoleEnum::ROLE_USER,
                $this->faker->firstName,
                $this->faker->lastName,
                DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-30 years', '-20 years')),
                $this->faker->city
            );
            $collection->add($this->userService->createNewUser($dto, false));
        }
        $manager->flush();
        return $collection;
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

    /**
     * @param Collection<Forum> $forums
     * @param Collection<User> $users
     * @param ObjectManager $objectManager
     * @return Collection<Topic>
     */
    public function createTopics(
        Collection $forums,
        Collection $users,
        ObjectManager $objectManager
    ): Collection {
        $topics = new ArrayCollection();
        foreach ($forums as $el) {
            for ($i = 0; $i < $this->faker->numberBetween(2, 8); $i++) {
                $author = self::selectRandomUser($users);
                $dto = new TopicDto();
                $dto->setTitle($this->faker->word)
                    ->setContent($this->faker->sentence);
                $topic = $this->messageService->createNewTopic($dto, $el, $author);
                $topics->add($topic);
            }
        }
        $objectManager->flush();
        return $topics;
    }

    /**
     * @param Collection<Topic> $topics
     * @param Collection<User> $users
     * @param ObjectManager $objectManager
     * @return void
     */
    public function createPosts(
        Collection $topics,
        Collection $users,
        ObjectManager $objectManager
    ): void {
        foreach ($topics as $el) {
            for ($i = 0; $i < $this->faker->numberBetween(2, 8); $i++) {
                $author = self::selectRandomUser($users);
                $dto = new PostDto();
                $dto->setContent($this->faker->sentence);
                $topic = $this->messageService->createNewPost($dto, $el, $author);
                $topics->add($topic);
            }
        }
        $objectManager->flush();
    }

    /**
     * @param Collection<User> $users
     * @return User
     */
    public static function selectRandomUser(Collection $users): User
    {
        $array = $users->toArray();
        shuffle($array);
        return $array[0];
    }
}
