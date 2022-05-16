<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User\UserSendEditEmailService;
use App\DTO\User\UserEditInfoRequestDTO;

class UserEditInfoService
{
    private UserRepository $userRepository;
    private UserSendEditEmailService $userSendEditEmailService;

    public function __construct(
        UserRepository $userRepository, 
        UserSendEditEmailService $userSendEditEmailService 
    ) {
        $this->userRepository = $userRepository;
        $this->userSendEditEmailService = $userSendEditEmailService;
    }

    public function attemptToEditUserInfo(UserEditInfoRequestDTO $userEditInfoRequest, User $user): User
    {
        $user = $this->setUserProperties($userEditInfoRequest, $user);

        if ($userEditInfoRequest->getEmail() && $user->getEmail() != $userEditInfoRequest->getEmail()) {
            $this->userSendEditEmailService->sendUserEditEmailVerification(
                $userEditInfoRequest, 
                $user
            );
        }

        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(UserEditInfoRequestDTO $userEditInfoRequest, User $user): User 
    {
        if ($userEditInfoRequest->getName() && $user->getName() != $userEditInfoRequest->getName()) {
            $user->setName($userEditInfoRequest->getName());
        }

        return $user;
    }
}
