<?php

namespace App\Service\User;

use App\Entity\User;
use App\Entity\UserEmailNew;
use App\Repository\UserEmailNewRepository;
use App\Service\Email\EmailSendVerificationService;
use App\DTO\User\UserEditInfoRequestDTO;

class UserSendEditEmailService
{
    private UserEmailNewRepository $userEmailNewRepository;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        UserEmailNewRepository $userEmailNewRepository, 
        EmailSendVerificationService $emailSendVerificationService 
    ) {
        $this->userEmailNewRepository = $userEmailNewRepository;
        $this->emailSendVerificationService = $emailSendVerificationService;
    }

    public function sendUserEditEmailVerification(UserEditInfoRequestDTO $userEditInfoRequestDTO, User $user)
    {
        if ($userEmailNew = $this->userEmailNewRepository->findOneBy(["user" => $user])) {
            $this->userEmailNewRepository->remove($userEmailNew, true);
        }

        $this->emailSendVerificationService->sendEmailVerification($user, "user_edit_email_verify");
        
        $userEmailNew = $this->setUserEmailNewProperties($userEditInfoRequestDTO, new UserEmailNew(), $user);
        
        $this->userEmailNewRepository->add($userEmailNew, true);
    }

    private function setUserEmailNewProperties(
        UserEditInfoRequestDTO $userEditInfoRequestDTO, 
        UserEmailNew $userEmailNew,
        User $user
    ): UserEmailNew {
        $userEmailNew->setUser($user);
        $userEmailNew->setEmailNew($userEditInfoRequestDTO->getEmail());

        return $userEmailNew;
    }
}
