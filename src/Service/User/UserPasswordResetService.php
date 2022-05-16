<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserPasswordReset;
use App\Repository\UserPasswordResetRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\User\UserPasswordResetRequestDTO;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserPasswordResetService
{
    private UserPasswordResetRepository $userPasswordResetRepository;
    private EmailVerifyService $emailVerifyService;

    public function __construct(
        UserPasswordResetRepository $userPasswordResetRepository,
        EmailVerifyService $emailVerifyService
    ) {
        $this->userPasswordResetRepository = $userPasswordResetRepository;
        $this->emailVerifyService = $emailVerifyService;
    }

    public function attemptToResetUserPassword(EmailVerifyRequestDTO $emailVerifyRequestDTO)
    {
        $user = $this->emailVerifyService->attemptToVerifyEmail($emailVerifyRequestDTO);

        $userPasswordReset = $this->userPasswordResetRepository->findOneBy(["user" => $user->getId()]);

        $this->setUserProperties($userPasswordReset, $user);
        
        $this->userPasswordResetRepository->remove($userPasswordReset, true);
    }

    private function setUserProperties(UserPasswordReset $userPasswordReset, User $user): User 
    {
        $user->setPassword($userPasswordReset->getPasswordNew());

        return $user;
    }
}
