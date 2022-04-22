<?php

namespace App\Entity;

use App\Repository\DryCleaningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DryCleaningRepository::class)]
class DryCleaning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: UserProvider::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Rating::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?UserProvider
    {
        return $this->user;
    }

    public function setUser(?UserProvider $user): self
    {
        $this->user = $user;

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
}
