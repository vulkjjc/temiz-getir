<?php

namespace App\DTO\Phone;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;

class PhoneAddRequestDTO implements RequestDTOInterface
{
    #[Assert\Regex(pattern: "/^\+\d{1,5}\s\d{3}\s\d{3}\s\d{2}\s\d{2}$/", message: "Phone number is invalid.")]
    private string $phone;

    #[Assert\AtLeastOneOf([
        new Assert\Regex(pattern: "/^\+\d{1,5}\s\d{3}\s\d{3}\s\d{2}\s\d{2}$/"),
        new Assert\Blank
    ], includeInternalMessages: false, message: "Business phone number is invalid.")]
    private ?string $phoneBusiness;

    public function __construct(Request $request) 
    {
        $this->phone = $request->request->get("phone");
        $this->phoneBusiness = $request->request->get("phone-business");
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPhoneBusiness(): ?string
    {
        return $this->phoneBusiness;
    }
}
