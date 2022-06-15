<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CountryRepository;
use App\Service\User\UserSignupService;
use App\Service\Email\EmailSendVerificationService;
use App\DTO\UserProvider\UserProviderSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;
use App\DTO\Phone\PhoneAddRequestDTO;

class ProviderSignupRequestController extends AbstractController
{
    private CountryRepository $countryRepository;
    private UserSignupService $userSignupService;
    private EmailSendVerificationService $emailSendVerificationService;

    public function __construct(
        CountryRepository $countryRepository, 
        UserSignupService $userSignupService,
        EmailSendVerificationService $emailSendVerificationService
    ) {
        $this->countryRepository = $countryRepository;
        $this->userSignupService = $userSignupService;
        $this->emailSendVerificationService = $emailSendVerificationService;
    }

    #[Route("/signup/request/provider", name: "signup_request_provider", methods: ["GET"])]
    public function signupRequestProvider(): Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_request_provider.html.twig", ["countries" => $countries]);
    }

    #[Route("/signup/request/provider/init", name: "signup_request_provider_init", methods: ["POST"])]
    public function signupRequestProviderInit(
        UserProviderSignupRequestDTO $userProviderSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO,
        PhoneAddRequestDTO $phoneAddRequestDTO
    ): Response {
        $user = $this->userSignupService->attemptToSignupUser(
            $userProviderSignupRequestDTO,
            $locationAddRequestDTO,
            $phoneAddRequestDTO
        );

        $this->emailSendVerificationService->sendEmailVerification($user, "signup_verify");

        return $this->redirect(
            $this->generateUrl(
                "signup_request_provider", 
                ["success" => "Email verification sent successfully."]
            )
        );
    }
}
