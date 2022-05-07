<?php

namespace App\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Entity\Location;
use App\Service\Violation\ViolationService;
use App\Service\User\UserService;
use App\Service\Location\LocationAddService;
use App\Interface\DTO\User\UserSignupRequestDTOInterface;
use App\DTO\Location\LocationAddRequestDTO;

class UserSignupService
{
    private ManagerRegistry $doctrine;
    private UserPasswordHasherInterface $passwordHasher;

    private ViolationService $violationService;
    private UserService $userService;
    private LocationAddService $locationAddService;

    public function __construct(
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHasher,
        ViolationService $violationService,
        UserService $userService,
        LocationAddService $locationAddService
    ) {
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher;

        $this->violationService = $violationService;
        $this->userService = $userService;
        $this->locationAddService = $locationAddService;
    }

    public function attemptToSignupUser(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): User {
        $user = $this->setUserSignupProperties(
            $userSignupRequestDTO,
            new User(),
            $this->locationAddService->attemptToAddLocation($locationAddRequestDTO)
        );

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
        Location $location
    ): User {
        $user->setName($userSignupRequestDTO->getName());
        $user->setEmail($userSignupRequestDTO->getEmail());
        $user->setPassword($userSignupRequestDTO->getPassword());
        $user->setRoles([$userSignupRequestDTO->getUserRole()]);
        $user->setLocation($location);

        return $user;
    }
}
