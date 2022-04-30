<?php

namespace App\Service\UserCustomer;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\Location;
use App\Entity\User;
use App\Service\User\UserService;
use App\Service\Violation\ViolationService;
use App\DTO\UserCustomer\UserCustomerSignupRequestDTO;

class UserCustomerSignupService
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

    public function attemptToSignupUserCustomer(
        UserCustomerSignupRequestDTO $userCustomerSignupRequestDTO,
        Location $location
    ): User {
        $user = new User();
        $user = $this->setUserCustomerSignupProperties(
            $userCustomerSignupRequestDTO,
            $user,
            $location
        );

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->signupUserCustomer($user);

        return $user;
    }

    private function signupUserCustomer(User $user)
    {
        $user = $this->userService->hashUserPassword($user);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    private function setUserCustomerSignupProperties(
        UserCustomerSignupRequestDTO $userCustomerSignupRequestDTO,
        User $user,
        Location $location
    ): User {
        $user->setName($userCustomerSignupRequestDTO->name);
        $user->setEmail($userCustomerSignupRequestDTO->email);
        $user->setPassword($userCustomerSignupRequestDTO->password);
        $user->setLocation($location);

        return $user;
    }
}
