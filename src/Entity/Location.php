<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    private $country;

    #[ORM\ManyToOne(targetEntity: City::class)]
    private $city;

    #[ORM\ManyToOne(targetEntity: Province::class)]
    private $provice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->provice;
    }

    public function setProvince(?Province $provice): self
    {
        $this->provice = $provice;

        return $this;
    }
}
