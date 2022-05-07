<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Rating::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $rating;

    #[ORM\ManyToOne(targetEntity: Ironing::class, cascade: ['persist', 'remove'])]
    private $ironing;

    #[ORM\ManyToOne(targetEntity: DryCleaning::class, cascade: ['persist', 'remove'])]
    private $dryCleaning;

    #[ORM\ManyToOne(targetEntity: ShoeCleaning::class, cascade: ['persist', 'remove'])]
    private $shoeCleaning;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getIroning(): ?Ironing
    {
        return $this->ironing;
    }

    public function setIroning(?Ironing $ironing): self
    {
        $this->ironing = $ironing;

        return $this;
    }

    public function getDryCleaning(): ?DryCleaning
    {
        return $this->dryCleaning;
    }

    public function setDryCleaning(?DryCleaning $dryCleaning): self
    {
        $this->dryCleaning = $dryCleaning;

        return $this;
    }

    public function getShoeCleaning(): ?ShoeCleaning
    {
        return $this->shoeCleaning;
    }

    public function setShoeCleaning(?ShoeCleaning $shoeCleaning): self
    {
        $this->shoeCleaning = $shoeCleaning;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
