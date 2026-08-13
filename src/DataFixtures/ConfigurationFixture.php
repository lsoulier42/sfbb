<?php

namespace App\DataFixtures;

use App\Contract\Service\ConfigurationServiceInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigurationFixture extends Fixture
{
    public function __construct(
        private readonly ConfigurationServiceInterface $configurationService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $configs = [
            'forum_name' => 'GeekZone',
            'forum_description' => 'Un forum communautaire pour discuter cinéma, séries, mangas,'
                . ' jeux vidéo et développement.'
        ];
        $this->configurationService->setConfigurationsFromArray($configs);
    }
}
