<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route("/service/dry-cleaning", name: "dry_cleaning_service", methods: ["GET"])]
    public function dryCleaningService(): Response
    {
        return $this->render("services/dry_cleaning.html.twig");
    }

    #[Route("/service/carpet-cleaning", name: "carpet_cleaning_service", methods: ["GET"])]
    public function carpetCleaningService(): Response
    {
        return $this->render("services/carpet_cleaning.html.twig");
    }

    #[Route("/service/ironing", name: "ironing_service", methods: ["GET"])]
    public function ironingService(): Response
    {
        return $this->render("services/ironing.html.twig");
    }
}
