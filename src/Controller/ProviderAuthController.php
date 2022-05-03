<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\CountryRepository;
use App\Service\UserProvider\UserProviderSignupService;
use App\DTO\UserProvider\UserProviderSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;
use App\DTO\Service\ServiceAddRequestDTO;

class ProviderAuthController extends AbstractController
{
    private CountryRepository $countryRepository;
    private UserProviderSignupService $userProviderSignupService;

    public function __construct(
        CountryRepository $countryRepository,
        UserProviderSignupService $userProviderSignupService
    ) {
        $this->countryRepository = $countryRepository;
        $this->userProviderSignupService = $userProviderSignupService;
    }

    #[Route("/login/provider", name: "login_provider", methods: ["GET", "POST"])]
    public function loginProvider(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("auth/login_provider.html.twig", ["error" => $error, "last_username" => $lastUsername]);
    }

    #[Route("/signup/provider", name: "signup_provider", methods: ["GET"])]
    public function signupProvider() : Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_provider.html.twig", ["countries" => $countries]);
    }

    #[Route("/signup/provider/init", name: "signup_provider_init", methods: ["POST"])]
    public function signupProviderInit(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO
    ): Response {
        try {
            $this->userProviderSignupService->attemptToSignupUserProvider(
                $userProviderSignupRequestDTO,
                $locationAddRequestDTO
            );

            return $this->redirect($this->generateUrl("login_provider"));
        } catch (BadRequestHttpException $error) {
            return new Response($error->getMessage(), 403);
        }
    }
}
