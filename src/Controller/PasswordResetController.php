<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\User\UserSendPasswordResetService;
use App\Service\User\UserPasswordResetService;
use App\DTO\User\UserSendPasswordResetRequestDTO;
use App\DTO\Email\EmailVerifyRequestDTO;

class PasswordResetController extends AbstractController
{
    private UserSendPasswordResetService $userSendPasswordResetService;
    private UserPasswordResetService $userPasswordResetService;

    public function __construct(
        UserSendPasswordResetService $userSendPasswordResetService,
        UserPasswordResetService $userPasswordResetService,
    ) {
        $this->userSendPasswordResetService = $userSendPasswordResetService;
        $this->userPasswordResetService = $userPasswordResetService;
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset(): Response
    {
        return $this->render("auth/password_reset.html.twig");
    }

    #[Route("/password/reset/init", name: "password_reset_init", methods: ["POST"])]
    public function passwordResetInit(UserSendPasswordResetRequestDTO $userSendPasswordResetRequestDTO): Response
    {
        $this->userSendPasswordResetService->attemptToSendPasswordReset($userSendPasswordResetRequestDTO);
        
        return $this->redirect($this->generateUrl("password_reset", ["success" => "Email sent successfully."]));
    }

    #[Route("/password/verify", name: "password_verify", methods: ["GET"])]
    public function passwordVerify(EmailVerifyRequestDTO $emailVerifyRequestDTO): Response
    {
        $this->userPasswordResetService->attemptToResetUserPassword($emailVerifyRequestDTO);

        return $this->redirectToRoute("login", ["success" => "Password reset successfully."]);
    }
}
