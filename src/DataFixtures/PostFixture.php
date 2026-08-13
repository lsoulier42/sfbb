<?php

namespace App\DataFixtures;

use App\Contract\Service\MessageServiceInterface;
use App\Dto\Topic\PostDto;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PostFixture extends Fixture implements DependentFixtureInterface
{
    private const POST_CONTENTS = [
        'Merci pour ce sujet, je suis plutôt d\'accord avec ce qui a été dit.'
        . ' J\'attends de voir les autres avis !',
        'Bonne question ! De mon côté, je dirais que ça dépend surtout du contexte.',
        'Je n\'y avais jamais pensé sous cet angle, c\'est une bonne piste.',
        'Perso je ne suis pas totalement d\'accord, mais je comprends l\'argument.'
        . ' Belle discussion en tout cas !',
        'Très intéressant, je vais creuser le sujet de mon côté.',
        'Ça me rappelle une discussion similaire qu\'on avait eue ici,'
        . ' j\'étais plutôt du même avis.',
        'Je plussoie ! C\'est exactement ce que je me disais en lisant le premier message.',
        'J\'avoue ne pas être très calé sur le sujet, mais je suis content'
        . ' d\'avoir trouvé cette discussion.',
        'Hâte de voir la suite du débat, je reste dans le coin.',
        'Je suis de retour sur le forum après une pause, et c\'est le premier sujet que je lis.'
        . ' Bienvenue au lanceur !',
        'Bonnes remarques dans les réponses précédentes, je n\'ai pas grand-chose à ajouter'
        . ' si ce n\'est : merci pour le contenu.',
        'Je relance le débat avec une question : quelqu\'un a-t-il déjà testé une autre approche ?'
    ];

    private Generator $faker;

    public function __construct(
        private readonly MessageServiceInterface $messageService
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [TopicFixture::class, UserFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $users = $manager->getRepository(User::class)->findAll();
        $topics = $manager->getRepository(Topic::class)->findAll();
        foreach ($topics as $topic) {
            $count = $this->faker->numberBetween(2, 8);
            for ($i = 0; $i < $count; $i++) {
                $author = $users[array_rand($users)];
                $dto = new PostDto();
                $dto->setContent(self::randomElement(self::POST_CONTENTS));
                $this->messageService->createNewPost($dto, $topic, $author);
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
