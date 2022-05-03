<?php

namespace App\DTO\UserProvider;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Interface\DTO\UserSignupRequestDTOInterface;

class UserProviderSignupRequestDTO implements RequestDTOInterface, UserSignupRequestDTOInterface
{
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]{1,25}$/", message: "Username is invalid.")]
    private string $name;

    #[Assert\Email(message: "Email is invalid.")]
    private string $email;

    #[Assert\Length(min: "8", minMessage: "Password should be at least 8 characters.")]
    #[Assert\NotCompromisedPassword(message: "This password has been leaked. Please use another password.")]
    private string $password;

    #[Assert\EqualTo(propertyPath: "password", message: "Passwords do not match.")]
    private string $passwordRepeat;

    public function __construct(Request $request) {
        $this->name = $request->request->get("name");
        $this->email = $request->request->get("email");
        $this->password = $request->request->get("password");
        $this->passwordRepeat = $request->request->get("password-repeat");
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }
}
