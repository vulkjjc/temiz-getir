<?php

namespace App\Entity;

use App\Repository\UserProviderInfoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProviderInfoRepository::class)]
class UserProviderInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $supplyAmount;

    #[ORM\Column(type: 'boolean')]
    private $computerAvailable;

    #[ORM\Column(type: 'boolean')]
    private $deliveryAvailable;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplyAmount(): ?int
    {
        return $this->supplyAmount;
    }

    public function setSupplyAmount(int $supplyAmount): self
    {
        $this->supplyAmount = $supplyAmount;

        return $this;
    }

    public function isComputerAvailable(): ?bool
    {
        return $this->computerAvailable;
    }

    public function setComputerAvailable(bool $computerAvailable): self
    {
        $this->computerAvailable = $computerAvailable;

        return $this;
    }

    public function isDeliveryAvailable(): ?bool
    {
        return $this->deliveryAvailable;
    }

    public function setDeliveryAvailable(bool $deliveryAvailable): self
    {
        $this->deliveryAvailable = $deliveryAvailable;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
