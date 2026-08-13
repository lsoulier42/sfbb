<?php

namespace App\DataFixtures;

use App\Contract\Service\ForumServiceInterface;
use App\Entity\Category;
use App\Entity\Forum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ForumFixture extends Fixture implements DependentFixtureInterface
{
    private const TITLES = [
        'Cinema' => ['Star wars', 'Le seigneur des anneaux'],
        'Manga' => ['L\'attaque des titans', 'Naruto', 'One Piece'],
        'Jeux vidéo' => ['7 days to die', 'Minecraft', 'League of legends'],
        'Séries' => ['The Mandalorian', 'The Last of Us'],
        'Dév' => ['Web', 'C++', 'Python']
    ];

    private const SUBTITLES = [
        'Star wars' => 'Tout sur une galaxie lointaine, très lointaine',
        'Le seigneur des anneaux' => 'La Terre du Milieu et ses peuples, des Hobbits au Mordor',
        'L\'attaque des titans' => 'L\'humanité aux prises avec les Titans, en manga et en anime',
        'Naruto' => 'Les ninjas de Konoha et le destin du héros à la cape orange',
        'One Piece' => 'À la poursuite du One Piece avec l\'équipage au chapeau de paille',
        '7 days to die' => 'Survie et zombies : construisez votre base avant la nuit 7',
        'Minecraft' => 'Constructions, survie et aventures en blocs',
        'League of legends' => 'Mains, patchs et rangs de la Faille de l\'invocateur',
        'The Mandalorian' => 'Les aventures de Din Djarin et de l\'Enfant dans la galaxie',
        'The Last of Us' => 'Le cordyceps, la survie et l\'émotion, du jeu à la série',
        'Web' => 'Développement web : front, back, outils et bonnes pratiques',
        'C++' => 'Le langage C++ et son écosystème, du moderne au legacy',
        'Python' => 'Python pour la data, le web et le scripting'
    ];

    public function __construct(
        private readonly ForumServiceInterface $forumService
    ) {
    }

    public function getDependencies(): array
    {
        return [CategoryFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $categoriesByTitle = [];
        foreach ($manager->getRepository(Category::class)->findAll() as $category) {
            $categoriesByTitle[$category->getTitle()] = $category;
        }
        foreach (self::TITLES as $categoryTitle => $titles) {
            $category = $categoriesByTitle[$categoryTitle];
            foreach ($titles as $title) {
                $forum = new Forum();
                $forum->setTitle($title)
                    ->setCategory($category)
                    ->setSubTitle(self::SUBTITLES[$title]);
                $this->forumService->createNewForum($forum);
            }
        }
    }
}
