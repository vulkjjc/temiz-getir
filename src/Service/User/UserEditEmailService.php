<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Entity\UserEmailNew;
use App\Repository\UserRepository;
use App\Repository\UserEmailNewRepository;
use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class UserEditEmailService
{
    private UserRepository $userRepository;
    private UserEmailNewRepository $userEmailNewRepository;
    private EmailVerifyService $emailVerifyService;

    public function __construct(
        UserRepository $userRepository, 
        UserEmailNewRepository $userEmailNewRepository, 
        EmailVerifyService $emailVerifyService 
    ) {
        $this->userRepository = $userRepository;
        $this->userEmailNewRepository = $userEmailNewRepository;
        $this->emailVerifyService = $emailVerifyService;
    }

    public function attemptToEditUserEmail(EmailVerifyRequestDTO $emailVerifyRequestDTO): User
    {
        $user = $this->emailVerifyService->attemptToVerifyEmail($emailVerifyRequestDTO);
        
        $userEmailNew = $this->userEmailNewRepository->findOneBy(["user" => $user]);
        $user = $this->setUserProperties($userEmailNew, $user);

        $this->userEmailNewRepository->remove($userEmailNew, true);
        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(UserEmailNew $userEmailNew, User $user): User 
    {
        $user->setEmail($userEmailNew->getEmailNew());

        return $user;
    }
}
