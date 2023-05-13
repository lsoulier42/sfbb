<?php

namespace App\Dto\Admin;

use App\Entity\Configuration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ConfigurationCollection
{
    /**
     * @var Collection<Configuration>
     */
    private Collection $configurations;

    public function __construct(array $allConfigurations)
    {
        $this->configurations = new ArrayCollection($allConfigurations);
    }

    /**
     * @return Collection<Configuration>
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    /**
     * @param Collection<Configuration> $configurations
     * @return $this
     */
    public function setConfigurations(Collection $configurations): ConfigurationCollection
    {
        $this->configurations = $configurations;
        return $this;
    }

    public function addConfiguration(Configuration $configuration): ConfigurationCollection
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
        }
        return $this;
    }

    public function removeConfiguration(Configuration $configuration): ConfigurationCollection
    {
        if ($this->configurations->contains($configuration)) {
            $this->configurations->removeElement($configuration);
        }
        return $this;
    }
}
