<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route("/login", name: "login", methods: ["GET"])]
    public function login() : Response
    {
        return $this->render("auth/login.html.twig");
    }

    #[Route("/signup", name: "signup", methods: ["GET"])]
    public function signup() : Response
    {
        return $this->render("auth/signup.html.twig");
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset() : Response
    {
        return $this->render("auth/password_reset.html.twig");
    }
}
