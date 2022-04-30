<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends AbstractController
{
    #[Route("/login/choice", name: "login_choice", methods: ["GET"])]
    public function loginChoice() : Response
    {
        return $this->render("auth/login_choice.html.twig");
    }

    #[Route("/signup/choice", name: "signup_choice", methods: ["GET"])]
    public function signupChoice() : Response
    {
        return $this->render("auth/signup_choice.html.twig");
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset() : Response
    {
        return $this->render("auth/password_reset.html.twig");
    }

    #[Route("/logout", name: "logout", methods: ["GET"])]
    public function logout(): Response {}
}
