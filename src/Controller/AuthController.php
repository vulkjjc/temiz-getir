<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\AuthService;
use App\Repository\CountryRepository;

class AuthController extends AbstractController
{
    private AuthService $authService;
    private CountryRepository $countryRepository;

    public function __construct(AuthService $authService, CountryRepository $countryRepository)
    {
        $this->authService = $authService;
        $this->countryRepository = $countryRepository;
    }

    #[Route("/login", name: "login", methods: ["GET", "POST"])]
    public function login() : Response
    {
        return $this->render("auth/login.html.twig");
    }

    #[Route("/signup/choice", name: "signup_choice", methods: ["GET"])]
    public function signupChoice() : Response
    {
        return $this->render("auth/signup_choice.html.twig");
    }

    #[Route("/signup/customer", name: "signup_customer", methods: ["GET"])]
    public function signupCustomer() : Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_customer.html.twig", ["countries" => $countries]);
    }

    #[Route("/signup/provider", name: "signup_provider", methods: ["GET"])]
    public function signupProvider() : Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_provider.html.twig", ["countries" => $countries]);
    }

    #[Route("/password/reset", name: "password_reset", methods: ["GET"])]
    public function passwordReset() : Response
    {
        return $this->render("auth/password_reset.html.twig");
    }
}
