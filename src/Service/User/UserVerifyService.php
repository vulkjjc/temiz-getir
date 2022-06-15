<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserProviderRequest;
use App\Repository\UserRepository;
use App\Repository\UserProviderRequestRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserVerifyService
{
    private EmailVerifyService $emailVerifyService;
    private UserRepository $userRepository;
    private UserProviderRequestRepository $userProviderRequestRepository;

    public function __construct(
        EmailVerifyService $emailVerifyService, 
        UserRepository $userRepository,
        UserProviderRequestRepository $userProviderRequestRepository
    ) {
        $this->emailVerifyService = $emailVerifyService;
        $this->userRepository = $userRepository;
        $this->userProviderRequestRepository = $userProviderRequestRepository;
    }

    public function attemptToVerifyUser(EmailVerifyRequestDTO $emailVerifyRequestDTO)
    {
        $user = $this->emailVerifyService->attemptToVerifyEmail($emailVerifyRequestDTO);

        $user = $this->setUserProperties($user);

        $this->userRepository->add($user, true);

        if (in_array("ROLE_PROVIDER", $user->getRoles())) {
            $userProviderRequest = $this->setUserProviderRequestProperties(
                new UserProviderRequest(), 
                $user
            );
            
            $this->userProviderRequestRepository->add($userProviderRequest, true);
        }
    }

    private function setUserProperties(User $user): User
    {
        $user->setIsVerified(true);

        return $user;
    }

    private function setUserProviderRequestProperties(
        UserProviderRequest $userProviderRequest, 
        User $user
    ): UserProviderRequest {
        $userProviderRequest->setUser($user);

        return $userProviderRequest;
    }
}
