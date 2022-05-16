<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\User\UserVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class SignupController extends AbstractController
{
    private UserVerifyService $userVerifyService;

    public function __construct(UserVerifyService $userVerifyService) 
    {
        $this->userVerifyService = $userVerifyService;
    }

    #[Route("/signup/choice", name: "signup_choice", methods: ["GET"])]
    public function signupChoice() : Response
    {
        return $this->render("auth/signup_choice.html.twig");
    }

    #[Route("/signup/verify", name: "signup_verify", methods: ["GET"])]
    public function signupVerify(EmailVerifyRequestDTO $emailVerifyRequestDTO): Response
    {
        $this->userVerifyService->attemptToVerifyUser($emailVerifyRequestDTO);

        return $this->redirectToRoute("login", ["success" => "Email verified successfully."]);
    }
}
