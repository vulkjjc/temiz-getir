<?php

namespace App\Service\User;

use App\Repository\UserRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserDeleteService
{
    private UserRepository $userRepository;
    private EmailVerifyService $emailVerifyService;

    public function __construct(UserRepository $userRepository, EmailVerifyService $emailVerifyService) 
    {
        $this->userRepository = $userRepository;
        $this->emailVerifyService = $emailVerifyService;
    }

    public function attemptToDeleteUser(EmailVerifyRequestDTO $emailVerifyRequestDTO) 
    {
        $user = $this->emailVerifyService->attemptToVerifyEmail($emailVerifyRequestDTO);

        $this->userRepository->remove($user, true);
    }
}
