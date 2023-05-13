<?php

namespace App\Service;

use App\Contract\Service\ConfigurationServiceInterface;
use App\Dto\Admin\ConfigurationCollection;
use App\Entity\Configuration;
use App\Repository\ConfigurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use LogicException;

class ConfigurationService implements ConfigurationServiceInterface
{
    public function __construct(
        private readonly ConfigurationRepository $configurationRepository
    ) {
    }

    public function getConfig(string $key): Configuration
    {
        $configuration = $this->configurationRepository->findByConfigKey($key);
        if (!$configuration instanceof Configuration) {
            throw new LogicException("Configuration '$key' not found");
        }
        return $configuration;
    }

    public function getConfigValue(string $key): string
    {
        return $this->getConfig($key)->getConfigValue();
    }

    public function setConfigValue(string $key, string $value, bool $flush = true): Configuration
    {
        $configuration = new Configuration();
        $configuration->setConfigKey($key)
            ->setConfigValue($value);
        $this->configurationRepository->createOrUpdate($configuration, $flush);
        return $configuration;
    }

    public function removeConfig(string|Configuration $config, bool $flush = true): void
    {
        if (!$config instanceof Configuration) {
            $config = $this->getConfig($config);
        }
        $this->configurationRepository->remove($config, $flush);
    }

    /**
     * @inheritDoc
     */
    public function setConfigurationsFromArray(array $array): Collection
    {
        $collection = new ArrayCollection();
        $nbItems = count($array);
        $nbSets = 0;
        foreach ($array as $key => $value) {
            $configuration = $this->setConfigValue($key, $value, $nbSets++ === $nbItems - 1);
            $collection->add($configuration);
        }
        return $collection;
    }

    public function getConfigurationCollection(): ConfigurationCollection
    {
        return new ConfigurationCollection($this->configurationRepository->findAll());
    }

    public function computeConfigurationCollection(ConfigurationCollection $collection): void
    {
        $configurations = $collection->getConfigurations();
        $nbConfigurations = $configurations->count();
        foreach ($configurations as $index => $configuration) {
            $this->configurationRepository->createOrUpdate($configuration, $index === $nbConfigurations - 1);
        }
    }
}
