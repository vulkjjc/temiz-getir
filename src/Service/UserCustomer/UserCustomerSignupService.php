<?php

namespace App\Service\UserCustomer;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\Location;
use App\Entity\User;
use App\Entity\UserCustomer;
use App\Service\Violation\ViolationService;
use App\Service\User\UserSignupService;
use App\Service\Location\LocationAddService;
use App\DTO\UserCustomer\UserCustomerSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;

class UserCustomerSignupService
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

    public function attemptToSignupUserCustomer(
        UserCustomerSignupRequestDTO $userCustomerSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): User {
        $user = $this->userSignupService->attemptToSignupUser($userCustomerSignupRequestDTO);
        $location = $this->locationAddService->attemptToAddLocation($locationAddRequestDTO);

        $userCustomer = $this->setUserCustomerSignupProperties($user, new UserCustomer(), $location);

        if ($violation = $this->violationService->getLastViolation($userCustomer)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUserCustomer($userCustomer);

        return $user;
    }

    private function signupUserCustomer(UserCustomer $userCustomer)
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($userCustomer);
        $entityManager->flush();
    }

    private function setUserCustomerSignupProperties(
        User $user,
        UserCustomer $userCustomer,
        Location $location
    ): UserCustomer {
        $userCustomer->setUser($user);
        $user->setLocation($location);

        return $userCustomer;
    }
}
