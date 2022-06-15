<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\User;
use App\Repository\UserProviderRequestRepository;

class UserChecker implements UserCheckerInterface
{
    private UserProviderRequestRepository $userProviderRequestRepository;

    public function __construct(UserProviderRequestRepository $userProviderRequestRepository)
    {
        $this->userProviderRequestRepository = $userProviderRequestRepository;
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->getIsVerified()) {
            throw new CustomUserMessageAccountStatusException("Email is not verified.");
        }

        if (
            in_array("ROLE_PROVIDER", $user->getRoles()) 
            && $this->userProviderRequestRepository->findOneBy(["user" => $user->getId()])
        ) {
            throw new CustomUserMessageAccountStatusException("This account is pending to be verified by our team.");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }
}
