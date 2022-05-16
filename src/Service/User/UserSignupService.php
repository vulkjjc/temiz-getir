<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Entity\Location;
use App\Repository\UserRepository;
use App\Service\Violation\ViolationService;
use App\Service\Location\LocationAddService;
use App\Service\Email\EmailSendVerificationService;
use App\Interface\DTO\User\UserSignupRequestDTOInterface;
use App\DTO\Location\LocationAddRequestDTO;

class UserSignupService
{
    private UserPasswordHasherInterface $passwordHasher;

    private UserRepository $userRepository;
    private ViolationService $violationService;
    private LocationAddService $locationAddService;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        ViolationService $violationService,
        LocationAddService $locationAddService,
        EmailSendVerificationService $emailSendVerificationService
    ) {
        $this->passwordHasher = $passwordHasher;

        $this->userRepository = $userRepository;
        $this->violationService = $violationService;
        $this->locationAddService = $locationAddService;
        $this->emailSendVerificationService = $emailSendVerificationService;
    }

    public function attemptToSignupUser(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): User {
        $user = $this->setUserProperties(
            $userSignupRequestDTO,
            new User(),
            $this->locationAddService->attemptToAddLocation($locationAddRequestDTO)
        );

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->userRepository->add($user, true);

        $this->emailSendVerificationService->sendEmailVerification($user, "signup_verify");

        return $user;
    }

    private function setUserProperties(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        User $user,
        Location $location
    ): User {
        $user->setName($userSignupRequestDTO->getName());
        $user->setEmail($userSignupRequestDTO->getEmail());
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userSignupRequestDTO->getPassword()
            )
        );
        $user->setRoles([$userSignupRequestDTO->getUserRole()]);
        $user->setLocation($location);

        return $user;
    }
}
