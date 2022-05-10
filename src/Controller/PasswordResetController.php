<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset(): Response
    {
        return $this->render("auth/password_reset.html.twig");
    }
}
