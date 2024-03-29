<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CountryRepository;
use App\Service\User\UserSignupService;
use App\Service\Email\EmailSendVerificationService;
use App\DTO\UserCustomer\UserCustomerSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;
use App\DTO\Phone\PhoneAddRequestDTO;

class CustomerSignupController extends AbstractController
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

    #[Route("/signup/customer", name: "signup_customer", methods: ["GET"])]
    public function signupCustomer() : Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("auth/signup_customer.html.twig", ["countries" => $countries]);
    }

    #[Route("/signup/customer/init", name: "signup_customer_init", methods: ["POST"])]
    public function signupCustomerInit(
        UserCustomerSignupRequestDTO $userCustomerSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO,
        PhoneAddRequestDTO $phoneAddRequestDTO
    ): Response {
        $user = $this->userSignupService->attemptToSignupUser(
            $userCustomerSignupRequestDTO,
            $locationAddRequestDTO,
            $phoneAddRequestDTO
        );

        $this->emailSendVerificationService->sendEmailVerification($user, "signup_verify");

        return $this->redirect($this->generateUrl("login", ["success" => "Email verification sent successfully."]));
    }
}
