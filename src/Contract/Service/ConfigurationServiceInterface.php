<?php

namespace App\Contract\Service;

use App\Entity\Configuration;
use Doctrine\Common\Collections\Collection;

interface ConfigurationServiceInterface
{
    public function getConfig(string $key): Configuration;

    public function getConfigValue(string $key): string;

    public function setConfigValue(string $key, string $value, bool $flush = true): Configuration;

    public function removeConfig(string|Configuration $config, bool $flush = true): void;

    /**
     * @param array<string, string> $array
     * @return Collection<Configuration>
     */
    public function setConfigurationsFromArray(array $array): Collection;
}
