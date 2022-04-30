<?php

namespace App\Entity;

use App\Repository\IroningRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IroningRepository::class)]
class Ironing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Rating::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $rating;

    public function getId(): ?int
    {
        return $this->id;
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
