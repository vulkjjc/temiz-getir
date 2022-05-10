<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    #[Route("/signup/choice", name: "signup_choice", methods: ["GET"])]
    public function signupChoice() : Response
    {
        return $this->render("auth/signup_choice.html.twig");
    }
}
