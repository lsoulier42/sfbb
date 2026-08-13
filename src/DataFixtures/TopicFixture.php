<?php

namespace App\DataFixtures;

use App\Contract\Service\MessageServiceInterface;
use App\Dto\Topic\TopicDto;
use App\Entity\Forum;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class TopicFixture extends Fixture implements DependentFixtureInterface
{
    private const TOPIC_TITLES = [
        'Star wars' => [
            'Quel est votre film préféré de la saga ?',
            'Les prélogies sont-elles vraiment si mauvaises ?',
            'Votre tier list des Jedi, du plus puissant au plus contesté',
            'Ahsoka mérite-t-elle un meilleur accueil que la série ne le laisse penser ?',
            'Ordre de visionnage : par date de sortie ou chronologique ?',
            'Le meilleur méchant de la saga, selon vous',
            'L\'Épisode IX : votre avis sur la fin de la saga Skywalker',
            'Les séries Star Wars valent-elles le coup pour les fans des films ?'
        ],
        'Le seigneur des anneaux' => [
            'L\'édition longue est-elle la seule façon de regarder la trilogie ?',
            'Les anneaux de pouvoir : votre avis honnête sur la série ?',
            'Le personnage le plus sous-estimé de la Terre du Milieu',
            'En quel ordre faut-il découvrir les livres et les films ?',
            'Frodon ou Sam : qui porte vraiment la quête ?',
            'Votre scène préférée de la trilogie',
            'La bataille du gouffre de Helm reste-t-elle la meilleure scène de bataille ?'
        ],
        'L\'attaque des titans' => [
            'Théorie : l\'origine du Titan fondateur décryptée',
            'Quel est le meilleur arc de l\'anime ?',
            'Le manga est-il nettement mieux que l\'anime ?',
            'Votre top des combats de la série',
            'Eren : héros, victime ou véritable antagoniste ?',
            'La fin de l\'histoire vous a-t-elle convaincu ?',
            'Les musiques de la série font-elles partie de son succès ?'
        ],
        'Naruto' => [
            'Shippuden ou la première partie : que préférez-vous ?',
            'Les personnages féminins sont-ils sous-exploités dans le manga ?',
            'Le débat qui divise : Sasuke avait-il finalement raison ?',
            'Votre jutsu préféré et pourquoi',
            'Quel ninja aurait mérité une série dérivée ?',
            'Boruto ternit-il l\'héritage de Naruto ?',
            'Les arcs de remplissage valent-ils le coup en 2026 ?'
        ],
        'One Piece' => [
            'Où en êtes-vous dans le manga ? Attention aux spoilers !',
            'Les meilleurs arcs de la saga, votre classement',
            'Zoro ou Sanji : qui est le meilleur membre d\'équipage ?',
            'Luffy peut-il encore surprendre après tant de combats ?',
            'La fin du manga est-elle proche selon vous ?',
            'Votre fruit du démon préféré',
            'Les théories les plus folles sur les poneglyphes'
        ],
        '7 days to die' => [
            'Meilleure base de départ pour survivre à la première nuit 7 ?',
            'Astuces pour farmer efficacement les premières semaines',
            'Sang-mêlé ou zombies classiques : quel mode de jeu ?',
            'La version 1.0 change-t-elle vraiment la donne ?',
            'Votre avis sur la progression des compétences',
            'Jouer seul ou en serveur : que conseillez-vous ?',
            'Les meilleurs mods de la communauté'
        ],
        'Minecraft' => [
            'Montrez vos constructions, on veut tout voir !',
            'Le Nether en 1.20 : votre guide et vos astuces',
            'Sans mods ou avec mods : que préférez-vous ?',
            'Survie longue durée : quels sont vos objectifs ?',
            'Votre biome préféré et pourquoi',
            'La redstone vous paraît-elle accessible ?',
            'Serveur communautaire : qui est intéressé ?'
        ],
        'League of legends' => [
            'Quel champion jouez-vous en ce moment ?',
            'Le meilleur et le pire du patch actuel',
            'Comment sortir de l\'élo silver ?',
            'Votre avis sur la saison en cours',
            'Jungler ou sololaner : quel rôle pour progresser ?',
            'Les skins : achat utile ou purement cosmétique ?',
            'Votre top des champions les plus amusants'
        ],
        'The Mandalorian' => [
            'La saison 3 : déception ou réussite ?',
            'Grogu est-il la vraie star de la série ?',
            'Le meilleur épisode de la série, selon vous',
            'La série doit-elle rester épisodique ou se sérialiser ?',
            'Le sabre d\'or de Bo-Katan : votre lecture de l\'intrigue',
            'Quand aura lieu la rencontre avec la saga principale ?',
            'Vos théories pour la saison suivante'
        ],
        'The Last of Us' => [
            'La série HBO est-elle fidèle au jeu ?',
            'Quelle partie est la meilleure : la première ou la seconde ?',
            'Votre avis sur l\'adaptation du deuxième jeu',
            'La performance des acteurs, un niveau au-dessus ?',
            'Le cordyceps est-il le vrai sujet de la série ?',
            'Les ajouts de la série apportent-ils quelque chose au récit ?',
            'Le mode multijoueur de la première partie vous manque-t-il ?'
        ],
        'Web' => [
            'Framework front : que choisir pour un nouveau projet en 2026 ?',
            'WordPress ou un framework PHP pour un site vitrine ?',
            'Vos bonnes pratiques pour sécuriser une application web',
            'Accessibilité web : par où commencer sérieusement ?',
            'API REST ou GraphQL pour votre prochain back-end ?',
            'L\'hébergement en 2026 : vos retours d\'expérience',
            'Comment testez-vous vos applications : vos outils préférés'
        ],
        'C++' => [
            'Ressources pour apprendre le C++ moderne par soi-même',
            'CMake ou Meson pour vos projets ?',
            'Smart pointers : vos astuces et pièges courants',
            'C++ est-il encore pertinent pour les nouveaux projets ?',
            'Votre avis sur les modules arrivés dans le langage',
            'Comment gérez-vous le legacy code en C++ ?',
            'Les pièges du multithreading que vous auriez aimé connaître'
        ],
        'Python' => [
            'Django ou FastAPI pour construire une API ?',
            'Vos projets Python du moment, montrez-nous !',
            'Le typing en Python : pratique courante ou prise de tête ?',
            'Python pour la data : votre stack favorite',
            'Virtualenv, uv ou poetry : votre gestionnaire de dépendances',
            'Comment structurez-vous vos scripts qui deviennent des projets ?',
            'Les astuces de performance que vous utilisez au quotidien'
        ]
    ];

    private const GENERIC_TOPIC_TITLES = [
        'Votre avis sur le sujet du moment ?',
        'Une question que je me pose depuis longtemps',
        'Qu\'est-ce qui vous a amené sur ce forum ?',
        'Conseils et retours d\'expérience bienvenus'
    ];

    private const TOPIC_CONTENTS = [
        'Salut la communauté, je crée ce sujet pour échanger avec vous sur ce thème.'
        . ' Je n\'ai pas trouvé d\'avis tranché ailleurs, alors je me suis dit'
        . ' que le forum était le meilleur endroit pour en discuter.',
        'Je suis nouveau sur le forum, excusez-moi si un sujet équivalent existe déjà.'
        . ' J\'ai fouillé les archives et je n\'ai rien trouvé de similaire,'
        . ' alors je me lance !',
        'Ce sujet me trottait dans la tête depuis un moment, je me suis dit qu\'il valait'
        . ' mieux le poser ici pour avoir vos avis. N\'hésitez pas à être francs !',
        'Petite question de ma part, j\'ouvre le débat : chacun a sa lecture du sujet'
        . ' et j\'aimerais bien connaître la vôtre.',
        'Je me demande ce que la communauté pense de ce point précis.'
        . ' Je lance le sujet, à vous de jouer !',
        'Ça fait un moment que je voulais en discuter, et le sujet n\'existant pas encore'
        . ' ici, je me permets de l\'ouvrir.',
        'Une discussion avec des amis m\'a fait réfléchir à ce thème,'
        . ' et je me suis dit que vos avis éclairés m\'aideraient à me faire une opinion.'
    ];

    private Generator $faker;

    public function __construct(
        private readonly MessageServiceInterface $messageService
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [UserFixture::class, ForumFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $forums = $manager->getRepository(Forum::class)->findAll();
        foreach ($forums as $forum) {
            $titles = self::TOPIC_TITLES[$forum->getTitle()] ?? self::GENERIC_TOPIC_TITLES;
            shuffle($titles);
            $count = $this->faker->numberBetween(2, min(8, count($titles)));
            foreach (array_slice($titles, 0, $count) as $title) {
                $author = $users[array_rand($users)];
                $dto = new TopicDto();
                $dto->setTitle($title)
                    ->setContent(self::randomElement(self::TOPIC_CONTENTS));
                $this->messageService->createNewTopic($dto, $forum, $author);
            }
        }
        $manager->flush();
    }

    /**
     * @param array<int, string> $array
     * @return string
     */
    private static function randomElement(array $array): string
    {
        return $array[array_rand($array)];
    }
}
