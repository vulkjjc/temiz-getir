<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CountryRepository;
use App\Service\User\UserSignupService;
use App\DTO\UserProvider\UserProviderSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;

class ProviderSignupController extends AbstractController
{
    private CountryRepository $countryRepository;
    private UserSignupService $userSignupService;

    public function __construct(CountryRepository $countryRepository, UserSignupService $userSignupService) 
    {
        $this->countryRepository = $countryRepository;
        $this->userSignupService = $userSignupService;
    }

    #[Route("/signup/provider", name: "signup_provider", methods: ["GET"])]
    public function signupProvider(): Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_provider.html.twig", ["countries" => $countries]);
    }

    #[Route("/signup/provider/init", name: "signup_provider_init", methods: ["POST"])]
    public function signupProviderInit(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): Response {
        $this->userSignupService->attemptToSignupUser(
            $userProviderSignupRequestDTO,
            $locationAddRequestDTO
        );

        return $this->redirect($this->generateUrl("login", ["success" => "Email verification sent successfully."]));
    }
}
