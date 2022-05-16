<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Email\EmailSendVerificationService;
use App\Service\User\UserEditInfoService;
use App\Service\User\UserEditEmailService;
use App\Service\User\UserEditPasswordService;
use App\Service\User\UserDeleteService;
use App\DTO\Email\EmailVerifyRequestDTO;
use App\DTO\User\UserEditInfoRequestDTO;
use App\DTO\User\UserEditPasswordRequestDTO;
use App\DTO\User\UserDeleteRequestDTO;

class UserController extends AbstractController
{
    private EmailSendVerificationService $emailSendVerificationService;
    private UserEditInfoService $userEditInfoService;
    private UserEditEmailService $userEditEmailService;
    private UserEditPasswordService $userEditPasswordService;
    private UserDeleteService $userDeleteService;

    public function __construct(
        EmailSendVerificationService $emailSendVerificationService,
        UserEditInfoService $userEditInfoService, 
        UserEditEmailService $userEditEmailService, 
        UserEditPasswordService $userEditPasswordService, 
        UserDeleteService $userDeleteService
    ) {
        $this->emailSendVerificationService = $emailSendVerificationService;
        $this->userEditInfoService = $userEditInfoService;
        $this->userEditEmailService = $userEditEmailService;
        $this->userEditPasswordService = $userEditPasswordService;
        $this->userDeleteService = $userDeleteService;
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

    #[Route("/user/delete", name: "user_delete", methods: ["DELETE"])]
    public function userDelete(UserDeleteRequestDTO $userDeleteRequest): Response
    {
        $this->emailSendVerificationService->sendEmailVerification($this->getUser(), "user_delete_verify");

        return $this->redirect($this->generateUrl("settings", ["success" => "Email verification sent successfully."]));
    }

    #[Route("/user/delete/verify", name: "user_delete_verify", methods: ["GET"])]
    public function userDeleteVerify(EmailVerifyRequestDTO $emailVerifyRequest): Response
    {
        $this->userDeleteService->attemptToDeleteUser($emailVerifyRequest);

        return $this->redirect($this->generateUrl("login", ["success" => "User deleted successfully."]));
    }
}
