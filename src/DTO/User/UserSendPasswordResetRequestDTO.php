<?php

namespace App\DTO\User;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\User as UserAssert;

class UserSendPasswordResetRequestDTO implements RequestDTOInterface
{
    #[UserAssert\UserEmail]
    #[UserAssert\UserVerifiedByEmail]
    private string $email;

    #[Assert\Length(min: "8", minMessage: "Password should be at least 8 characters.")]
    #[Assert\NotCompromisedPassword(message: "This password has been leaked. Please use another password.")]
    private string $passwordNew;

    #[Assert\EqualTo(propertyPath: "passwordNew", message: "Passwords do not match.")]
    private string $passwordNewRepeat;

    public function __construct(Request $request)
    {
        $this->email = $request->request->get("email");
        $this->passwordNew = $request->request->get("password-new");
        $this->passwordNewRepeat = $request->request->get("password-new-repeat");
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordNew(): string
    {
        return $this->passwordNew;
    }

    public function getPasswordNewRepeat(): string
    {
        return $this->passwordNewRepeat;
    }
}
