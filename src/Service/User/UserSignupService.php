<?php

namespace App\Service\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Entity\Location;
use App\Service\Violation\ViolationService;
use App\Service\User\UserService;
use App\Service\Location\LocationAddService;
use App\Service\Email\EmailSendVerificationService;
use App\Interface\DTO\User\UserSignupRequestDTOInterface;
use App\DTO\Location\LocationAddRequestDTO;

class UserSignupService
{
    private ManagerRegistry $doctrine;

    private ViolationService $violationService;
    private UserService $userService;
    private LocationAddService $locationAddService;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        ManagerRegistry $doctrine,
        ViolationService $violationService,
        UserService $userService,
        LocationAddService $locationAddService,
        EmailSendVerificationService $emailSendVerificationService
    ) {
        $this->doctrine = $doctrine;

        $this->violationService = $violationService;
        $this->userService = $userService;
        $this->locationAddService = $locationAddService;
        $this->emailSendVerificationService = $emailSendVerificationService;
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
        $this->emailSendVerificationService->sendEmailVerification($user);

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
