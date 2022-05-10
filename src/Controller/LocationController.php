<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Repository\CityRepository;
use App\Repository\ProvinceRepository;

class LocationController extends AbstractController
{
    private CityRepository $cityRepository;
    private ProvinceRepository $provinceRepository;

    public function __construct(CityRepository $cityRepository, ProvinceRepository $provinceRepository)
    {
        $this->cityRepository = $cityRepository;
        $this->provinceRepository = $provinceRepository;
    }

    #[Route("/cities/get/{id}", name: "get_cities", requirements: ["id" => "\d+"], methods: ["POST"])]
    public function getCities(Request $request, string $id): JsonResponse
    {
        $cities = $this->cityRepository->findCitiesAsArray($id);

        return new JsonResponse($cities);
    }

    #[Route("/provinces/get/{id}", name: "get_provinces", requirements: ["id" => "\d+"], methods: ["POST"])]
    public function getProvinces(Request $request, string $id): JsonResponse
    {
        $provinces = $this->provinceRepository->findProvincesAsArray($id);

        return new JsonResponse($provinces);
    }
}
