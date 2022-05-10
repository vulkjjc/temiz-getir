<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

use App\Service\Email\EmailVerifyService;
use App\DTO\Email\EmailVerifyRequestDTO;

class AuthController extends AbstractController
{
    private ManagerRegistry $doctrine;

    private EmailVerifyService $emailVerifyService;

    public function __construct(
        ManagerRegistry $doctrine,
        EmailVerifyService $emailVerifyService
    ) {
        $this->doctrine = $doctrine;

        $this->emailVerifyService = $emailVerifyService;
    }

    #[Route("/login", name: "login", methods: ["GET", "POST"])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("auth/login.html.twig", ["error" => $error, "last_username" => $lastUsername]);
    }

    #[Route("/signup/choice", name: "signup_choice", methods: ["GET"])]
    public function signupChoice() : Response
    {
        return $this->render("auth/signup_choice.html.twig");
    }

    #[Route("/email/verify", name: "email_verify", methods: ["GET"])]
    public function emailVerify(EmailVerifyRequestDTO $emailVerifyRequestDTO) : Response
    {
        try {
            $this->emailVerifyService->verifyEmail($emailVerifyRequestDTO);
        } catch (VerifyEmailExceptionInterface $exception) {
            return new Response($exception->getMessage());
        }

        return $this->redirectToRoute("login", ["success" => "Email verified successfully."]);
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset() : Response
    {
        return $this->render("auth/password_reset.html.twig");
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout(): Response {}
}
