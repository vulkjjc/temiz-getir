<?php

namespace App\Service\UserProvider;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\Location;
use App\Entity\User;
use App\Entity\UserProvider;
use App\Service\Violation\ViolationService;
use App\Service\User\UserSignupService;
use App\Service\Location\LocationAddService;
use App\DTO\UserProvider\UserProviderSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;

class UserProviderSignupService
{
    private ManagerRegistry $doctrine;

    private ViolationService $violationService;
    private UserSignupService $userSignupService;
    private LocationAddService $locationAddService;

    public function __construct(
        ManagerRegistry $doctrine,
        ViolationService $violationService,
        UserSignupService $userSignupService,
        LocationAddService $locationAddService
    ) {
        $this->doctrine = $doctrine;

        $this->violationService = $violationService;
        $this->userSignupService = $userSignupService;
        $this->locationAddService = $locationAddService;
    }

    public function attemptToSignupUserProvider(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): User {
        $user = $this->userSignupService->attemptToSignupUser($userProviderSignupRequestDTO);
        $location = $this->locationAddService->attemptToAddLocation($locationAddRequestDTO);

        $userProvider = $this->setUserProviderSignupProperties($user, new UserProvider(), $location);

        if ($violation = $this->violationService->getLastViolation($userProvider)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUserProvider($userProvider);

        return $user;
    }

    private function signupUserProvider(UserProvider $userProvider)
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($userProvider);
        $entityManager->flush();
    }

    private function setUserProviderSignupProperties(
        User $user,
        UserProvider $userProvider,
        Location $location
    ): UserProvider {
        $userProvider->setUser($user);
        $user->setLocation($location);

        return $userProvider;
    }
}
