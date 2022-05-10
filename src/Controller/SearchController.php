<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route("/search/services", name: "search_services", methods: ["GET"])]
    public function searchServices(): Response
    {
        return $this->render("search/services.html.twig");
    }
}
