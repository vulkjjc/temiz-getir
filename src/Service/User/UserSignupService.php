<?php

namespace App\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Service\Violation\ViolationService;
use App\Service\User\UserService;
use App\interface\DTO\UserSignupRequestDTOInterface;

class UserSignupService
{
    private ManagerRegistry $doctrine;
    private UserPasswordHasherInterface $passwordHasher;

    private ViolationService $violationService;
    private UserService $userService;

    public function __construct(
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        ViolationService $violationService,
        UserService $userService,
    ) {
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher;

        $this->violationService = $violationService;
        $this->userService = $userService;
    }

    public function attemptToSignupUser(UserSignupRequestDTOInterface $userSignupRequestDTO): User {
        $user = $this->setUserSignupProperties($userSignupRequestDTO, new User());

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUser($user);

        return $user;
    }

    private function signupUser(User $user)
    {
        $user = $this->userService->hashUserPassword($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserSignupProperties(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        User $user,
    ): User {
        $user->setName($userSignupRequestDTO->getName());
        $user->setEmail($userSignupRequestDTO->getEmail());
        $user->setPassword($userSignupRequestDTO->getPassword());

        return $user;
    }
}
