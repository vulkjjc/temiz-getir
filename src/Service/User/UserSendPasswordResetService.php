<?php

namespace App\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Entity\UserPasswordReset;
use App\Service\Email\EmailSendVerificationService;
use App\Repository\UserRepository;
use App\Repository\UserPasswordResetRepository;
use App\DTO\User\UserSendPasswordResetRequestDTO;

class UserSendPasswordResetService
{
    private UserPasswordHasherInterface $passwordHasher;

    private EmailSendVerificationService $emailSendVerificationService;
    private UserRepository $userRepository;
    private UserPasswordResetRepository $userPasswordResetRepository;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EmailSendVerificationService $emailSendVerificationService,
        UserRepository $userRepository,
        UserPasswordResetRepository $userPasswordResetRepository
    ) {
        $this->passwordHasher = $passwordHasher;

        $this->emailSendVerificationService = $emailSendVerificationService;
        $this->userRepository = $userRepository;
        $this->userPasswordResetRepository = $userPasswordResetRepository;
    }

    public function attemptToSendPasswordReset(UserSendPasswordResetRequestDTO $userSendPasswordResetRequestDTO): UserPasswordReset
    {
        $user = $this->userRepository->findOneBy(["email" => $userSendPasswordResetRequestDTO->getEmail()]);
        
        if ($userPasswordReset = $this->userPasswordResetRepository->findOneBy(["user" => $user])) {
            $this->userPasswordResetRepository->remove($userPasswordReset, true);
        }

        $this->emailSendVerificationService->sendEmailVerification($user, "password_verify");
        
        $userPasswordReset = $this->setUserPasswordResetProperties(
            $userSendPasswordResetRequestDTO,
            new UserPasswordReset(),
            $user
        );

        $this->userPasswordResetRepository->add($userPasswordReset, true);

        return $userPasswordReset;
    }

    private function setUserPasswordResetProperties(
        UserSendPasswordResetRequestDTO $userSendPasswordResetRequestDTO,
        UserPasswordReset $userPasswordReset,
        User $user
    ): UserPasswordReset {
        $userPasswordReset->setUser($user);
        $userPasswordReset->setPasswordNew(
            $this->passwordHasher->hashPassword(
                $user,
                $userSendPasswordResetRequestDTO->getPasswordNew()
            )
        );

        return $userPasswordReset;
    }
}
