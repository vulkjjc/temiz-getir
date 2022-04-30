<?php

namespace App\DTO\Service;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\Service as ServiceAssert;

class ServiceAddRequestDTO implements RequestDTOInterface
{
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]{1,25}$/", message: "Username is invalid.")]
    public string $name;

    #[ServiceAssert\DryCleaning]
    public string $dryCleaning;

    #[ServiceAssert\ShoeCleaning]
    public string $shoeCleaning;

    #[ServiceAssert\Ironing]
    public string $ironing;

    #[ServiceAssert\CarpetCleaning]
    public string $carpetCleaning;

    #[ServiceAssert\SheetCleaning]
    public string $sheetCleaning;

    public function __construct(Request $request) {
        $this->dryCleaning = $request->request->get("dry-cleaning");
        $this->shoeCleaning = $request->request->get("shoe-cleaning");
        $this->ironing = $request->request->get("ironing");
        $this->carpetCleaning = $request->request->get("carpet-cleaning");
        $this->sheetCleaning = $request->request->get("sheet-cleaning");
    }
}
