<?php

namespace App\Service\Email;

use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

use App\Entity\User;
use App\Repository\UserRepository;
use App\DTO\Email\EmailVerifyRequestDTO;

class EmailVerifyService
{
    private ManagerRegistry $doctrine;
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private UserRepository $userRepository;

    public function __construct(
        ManagerRegistry $doctrine,
        VerifyEmailHelperInterface $verifyEmailHelper,
        UserRepository $userRepository
    ) {
        $this->doctrine = $doctrine;
        $this->verifyEmailHelper = $verifyEmailHelper;
        
        $this->userRepository = $userRepository;
    }

    public function verifyEmail(EmailVerifyRequestDTO $emailVerifyRequestDTO)
    {
        $user = $this->userRepository->find($emailVerifyRequestDTO->getUserId());

        $this->verifyEmailHelper->validateEmailConfirmation(
            $emailVerifyRequestDTO->getRequestUri(),
            $user->getId(),
            $user->getEmail()
        );

        $this->verifyUser($user);
    }

    private function verifyUser(User $user)
    {
        $user->setIsVerified(true);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }
}
