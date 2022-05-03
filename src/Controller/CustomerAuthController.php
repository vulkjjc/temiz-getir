<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\CountryRepository;
use App\Service\UserCustomer\UserCustomerSignupService;
use App\DTO\UserCustomer\UserCustomerSignupRequestDTO;
use App\DTO\Location\LocationAddRequestDTO;

class CustomerAuthController extends AbstractController
{
    private CountryRepository $countryRepository;
    private UserCustomerSignupService $userCustomerSignupService;

    public function __construct(
        CountryRepository $countryRepository,
        UserCustomerSignupService $userCustomerSignupService
    ) {
        $this->countryRepository = $countryRepository;
        $this->userCustomerSignupService = $userCustomerSignupService;
    }

    #[Route("/login/customer", name: "login_customer", methods: ["GET", "POST"])]
    public function loginCustomer(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("auth/login_customer.html.twig", ["error" => $error, "last_username" => $lastUsername]);
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
        LocationAddRequestDTO $locationAddRequestDTO
    ): Response {
        try {
            $this->userCustomerSignupService->attemptToSignupUserCustomer(
                $userCustomerSignupRequestDTO,
                $locationAddRequestDTO
            );

            return $this->redirect($this->generateUrl("login_customer"));
        } catch (BadRequestHttpException $error) {
            return new Response($error->getMessage(), 403);
        }
    }
}
