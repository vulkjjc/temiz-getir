<?php

namespace App\Service\Location;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Repository\LocationRepository;
use App\Repository\CountryRepository;
use App\Repository\CityRepository;
use App\Repository\ProvinceRepository;
use App\Entity\Location;
use App\Service\Violation\ViolationService;
use App\DTO\Location\LocationAddRequestDTO;

class LocationAddService
{
    private ManagerRegistry $doctrine;

    private LocationRepository $locationRepository;
    private CountryRepository $countryRepository;
    private CityRepository $cityRepository;
    private ProvinceRepository $provinceRepository;
    private ViolationService $violationService;

    public function __construct(
        ManagerRegistry $doctrine,
        LocationRepository $locationRepository,
        CountryRepository $countryRepository,
        CityRepository $cityRepository,
        ProvinceRepository $provinceRepository,
        ViolationService $violationService,
    ) {
        $this->doctrine = $doctrine;

        $this->locationRepository = $locationRepository;
        $this->countryRepository = $countryRepository;
        $this->cityRepository = $cityRepository;
        $this->provinceRepository = $provinceRepository;
        $this->violationService = $violationService;
    }

    public function attemptToAddLocation(LocationAddRequestDTO $locationAddRequestDTO): Location
    {
        $location = new Location();
        $location = $this->setLocationAddProperties($locationAddRequestDTO, $location);

        if ($violation = $this->violationService->getLastViolation($location)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->locationRepository->add($location, true);

        return $location;
    }

    private function setLocationAddProperties(
        LocationAddRequestDTO $locationAddRequestDTO,
        Location $location
    ): Location {
        $location->setCountry($this->countryRepository->find($locationAddRequestDTO->getCountryId()));
        $location->setCity($this->cityRepository->find($locationAddRequestDTO->getCityId()));
        $location->setProvince($this->provinceRepository->find($locationAddRequestDTO->getProvinceId()));
        $location->setAddress($locationAddRequestDTO->getAddress());

        return $location;
    }
}
