<?php

namespace App\DTO\Location;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\Location as LocationAssert;

class LocationAddRequestDTO implements RequestDTOInterface
{
    #[LocationAssert\CountryId]
    private string $countryId;

    #[LocationAssert\CityId]
    private string $cityId;

    #[LocationAssert\ProvinceId]
    private string $provinceId;

    #[Assert\Regex(pattern: "/^.{1,125}$/", message: "Address is invalid.")]
    private string $address;

    public function __construct(Request $request) {
        $this->countryId = $request->request->get("country-id");
        $this->cityId = $request->request->get("city-id");
        $this->provinceId = $request->request->get("province-id");
        $this->address = $request->request->get("address");
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

    public function getAddress(): string
    {
        return $this->address;
    }
}
