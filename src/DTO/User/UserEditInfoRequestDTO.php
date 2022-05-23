<?php

namespace App\DTO\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\User as UserAssert;

class UserEditInfoRequestDTO implements RequestDTOInterface
{
    #[Assert\AtLeastOneOf([
        new Assert\Regex(pattern: "/^[a-zA-Z0-9_ ]{1,25}$/"),
        new Assert\Blank
    ], includeInternalMessages: false, message: "Name is invalid.")]
    private string $name;

    #[Assert\AtLeastOneOf([
        new UserAssert\UserEmailAvailable,
        new Assert\Blank
    ], includeInternalMessages: false, message: "Email is invalid.")]
    private string $email;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->name = $data["name"];
        $this->email = $data["email"];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
