<?php

namespace App\DTO\Location;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\Location as LocationAssert;

class LocationAddRequestDTO implements RequestDTOInterface
{
    #[LocationAssert\Country]
    private string $countryId;

    #[LocationAssert\City]
    private string $cityId;

    #[LocationAssert\Province]
    private string $provinceId;

    public function __construct(Request $request) {
        $this->countryId = $request->request->get("country-id");
        $this->cityId = $request->request->get("city-id");
        $this->provinceId = $request->request->get("province-id");
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function getCityId(): string
    {
        return $this->cityId;
    }

    public function getProvinceId(): string
    {
        return $this->provinceId;
    }
}
