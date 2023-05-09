<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity]
#[HasLifecycleCallbacks]
class Configuration extends AbstractEntity
{
    #[Column]
    private string $configKey;

    #[Column]
    private string $configValue;

    public function getConfigKey(): string
    {
        return $this->configKey;
    }

    public function setConfigKey(string $configKey): Configuration
    {
        $this->configKey = $configKey;
        return $this;
    }

    public function getConfigValue(): string
    {
        return $this->configValue;
    }

    public function setConfigValue(string $configValue): Configuration
    {
        $this->configValue = $configValue;
        return $this;
    }
}
