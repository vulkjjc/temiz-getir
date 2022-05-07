<?php

namespace App\Interface\DTO\User;

interface UserSignupRequestDTOInterface
{
    public function getName(): string;
    public function getEmail(): string;
    public function getPassword(): string;
    public function getPasswordRepeat(): string;
    public function getUserRole(): string;
}
