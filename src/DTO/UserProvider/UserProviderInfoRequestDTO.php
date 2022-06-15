<?php

namespace App\DTO\UserProvider;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;

class UserProviderInfoRequestDTO implements RequestDTOInterface
{
    #[Assert\Regex(pattern: "/^[1-8]$/", message: "Supply amount is invalid.")]
    private string $supplyAmount;

    #[Assert\Type(type: "boolean", message: "Computer available field is invalid.")]
    private string $computerAvailable;

    #[Assert\Type(type: "boolean", message: "Delivery available field is invalid.")]
    private string $deliveryAvailable;

    public function __construct(Request $request) 
    {
        $this->supplyAmount = $request->request->get("supply-amount");
        $this->computerAvailable = $request->request->get("computer-available");
        $this->deliveryAvailable = $request->request->get("delivery-available");
    }
    
    public function getSupplyAmount(): string
    {
        return $this->supplyAmount;
    }

    public function getComputerAvailable(): string
    {
        return $this->computerAvailable;
    }

    public function getDeliveryAvailable(): string
    {
        return $this->deliveryAvailable;
    }
}
