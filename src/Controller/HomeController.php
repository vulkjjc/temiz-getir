<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\CountryRepository;

class HomeController extends AbstractController
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository) 
    {
        $this->countryRepository = $countryRepository;
    }

    #[Route("/", name: "home", methods: ["GET"])]
    public function index(): Response
    {
        $countries = $this->countryRepository->findAll();

        return $this->render("home/index.html.twig", ["countries" => $countries]);
    }

    #[Route("/about", name: "about", methods: ["GET"])]
    public function about(): Response
    {
        return $this->render("home/about.html.twig");
    }

    #[Route("/contact", name: "contact", methods: ["GET"])]
    public function contact(): Response
    {
        return $this->render("home/contact.html.twig");
    }
}
