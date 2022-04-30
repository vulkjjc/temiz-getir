<?php

namespace App\Service\UserProvider;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\Location;
use App\Entity\User;
use App\Service\User\UserService;
use App\Service\Violation\ViolationService;
use App\DTO\UserProvider\UserProviderSignupRequestDTO;

class UserProviderSignupService
{
    private ManagerRegistry $doctrine;

    private ViolationService $violationService;
    private UserService $userService;

    public function __construct(
        ManagerRegistry $doctrine,
        ViolationService $violationService,
        UserService $userService
    ) {
        $this->doctrine = $doctrine;

        $this->violationService = $violationService;
        $this->userService = $userService;
    }

    public function attemptToSignupUserProvider(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        Location $location
    ): User {
        $user = new User();
        $user = $this->setUserProviderSignupProperties(
            $userProviderSignupRequestDTO,
            $user,
            $location
        );

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUserProvider($user);

        return $user;
    }

    private function signupUserProvider(User $user)
    {
        $user = $this->userService->hashUserPassword($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserProviderSignupProperties(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        User $user,
        Location $location
    ): User {
        $user->setName($userProviderSignupRequestDTO->name);
        $user->setEmail($userProviderSignupRequestDTO->email);
        $user->setPassword($userProviderSignupRequestDTO->password);
        $user->setLocation($location);

        return $user;
    }
}
