<?php

namespace App\Service\User;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserVerifyService
{
    private ManagerRegistry $doctrine;
    private EmailVerifyService $emailVerifyService;
    private UserRepository $userRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        EmailVerifyService $emailVerifyService,
        UserRepository $userRepository
    ) {
        $this->doctrine = $doctrine;
        $this->emailVerifyService = $emailVerifyService;
        
        $this->userRepository = $userRepository;
    }

    public function attemptToVerifyUser(EmailVerifyRequestDTO $emailVerifyRequestDTO)
    {
        $user = $this->emailVerifyService->attemptToVerifyEmail($emailVerifyRequestDTO);

        $user = $this->setUserProperties($user);

        $this->userRepository->add($user, true);
    }

    private function setUserProperties(User $user): User
    {
        $user->setIsVerified(true);

        return $user;
    }
}
