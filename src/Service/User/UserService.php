<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function hashUserPassword(UserInterface $user): UserInterface
    {
        $passwordHash = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($passwordHash);

        return $user;
    }
}
