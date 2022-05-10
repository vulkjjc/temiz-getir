<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\CountryRepository;
use App\Service\User\UserSignupService;
use App\DTO\UserCustomer\UserCustomerSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;

class CustomerAuthController extends AbstractController
{
    private CountryRepository $countryRepository;
    private UserSignupService $userSignupService;

    public function __construct(CountryRepository $countryRepository, UserSignupService $userSignupService) 
    {
        $this->countryRepository = $countryRepository;
        $this->userSignupService = $userSignupService;
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
    ): Response {
        $this->userSignupService->attemptToSignupUser(
            $userCustomerSignupRequestDTO,
            $locationAddRequestDTO
        );

        return $this->redirect($this->generateUrl("login"));
    }
}
