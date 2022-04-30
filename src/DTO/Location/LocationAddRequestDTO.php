<?php

namespace App\DTO\Location;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\Location as LocationAssert;

class LocationAddRequestDTO implements RequestDTOInterface
{
    #[LocationAssert\Country]
    public string $countryId;

    #[LocationAssert\City]
    public string $cityId;

    #[LocationAssert\Province]
    public string $provinceId;

    public function __construct(Request $request) {
        $this->countryId = $request->request->get("country-id");
        $this->cityId = $request->request->get("city-id");
        $this->provinceId = $request->request->get("province-id");
    }
}
