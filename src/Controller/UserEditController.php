<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Email\EmailSendVerificationService;
use App\Service\User\UserEditInfoService;
use App\Service\User\UserEditEmailService;
use App\Service\User\UserEditPasswordService;
use App\DTO\Email\EmailVerifyRequestDTO;
use App\DTO\User\UserEditInfoRequestDTO;
use App\DTO\User\UserEditPasswordRequestDTO;

class UserEditController extends AbstractController
{
    private EmailSendVerificationService $emailSendVerificationService;
    private UserEditInfoService $userEditInfoService;
    private UserEditEmailService $userEditEmailService;
    private UserEditPasswordService $userEditPasswordService;

    public function __construct(
        EmailSendVerificationService $emailSendVerificationService,
        UserEditInfoService $userEditInfoService, 
        UserEditEmailService $userEditEmailService, 
        UserEditPasswordService $userEditPasswordService, 
    ) {
        $this->emailSendVerificationService = $emailSendVerificationService;
        $this->userEditInfoService = $userEditInfoService;
        $this->userEditEmailService = $userEditEmailService;
        $this->userEditPasswordService = $userEditPasswordService;
    }

    #[Route("/user/edit/info", name: "user_edit_info", methods: ["PUT", "PATCH"])]
    public function userEditInfo(UserEditInfoRequestDTO $userEditInfoRequest): Response
    {
        $this->userEditInfoService->attemptToEditUserInfo($userEditInfoRequest, $this->getUser());

        return $this->redirect($this->generateUrl("settings", ["success" => "User information edited successfully."]));
    }

    #[Route("/user/edit/email/verify", name: "user_edit_email_verify", methods: ["GET"])]
    public function userEditEmailVerify(EmailVerifyRequestDTO $emailVerifyRequestDTO): Response
    {
        $this->userEditEmailService->attemptToEditUserEmail($emailVerifyRequestDTO, $this->getUser());

        return $this->redirect($this->generateUrl("settings", ["success" => "User information edited successfully."]));
    }

    #[Route("/user/edit/password", name: "user_edit_password", methods: ["PUT", "PATCH"])]
    public function userEditPassword(UserEditPasswordRequestDTO $userEditPasswordRequest): Response
    {
        $this->userEditPasswordService->attemptToEditUserPassword($userEditPasswordRequest, $this->getUser());

        return $this->redirect($this->generateUrl("settings", ["success" => "User password changed successfully."]));
    }
}
