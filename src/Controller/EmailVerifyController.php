<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class EmailVerifyController extends AbstractController
{
    private EmailVerifyService $emailVerifyService;

    public function __construct(EmailVerifyService $emailVerifyService) 
    {
        $this->emailVerifyService = $emailVerifyService;
    }

    #[Route("/email/verify", name: "email_verify", methods: ["GET"])]
    public function emailVerify(EmailVerifyRequestDTO $emailVerifyRequestDTO): Response
    {
        $this->emailVerifyService->verifyEmail($emailVerifyRequestDTO);

        return $this->redirectToRoute("login", ["success" => "Email verified successfully."]);
    }
}
