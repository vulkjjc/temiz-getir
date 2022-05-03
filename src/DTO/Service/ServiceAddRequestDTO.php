<?php

namespace App\DTO\Service;

use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\Service as ServiceAssert;

class ServiceAddRequestDTO implements RequestDTOInterface
{
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]{1,25}$/", message: "Username is invalid.")]
    private string $name;

    #[ServiceAssert\DryCleaning]
    private string $dryCleaningId;

    #[ServiceAssert\ShoeCleaning]
    private string $shoeCleaningId;

    #[ServiceAssert\Ironing]
    private string $ironingId;

    #[ServiceAssert\CarpetCleaning]
    private string $carpetCleaningId;

    #[ServiceAssert\SheetCleaning]
    private string $sheetCleaningId;

    public function __construct(Request $request) {
        $this->dryCleaningId = $request->request->get("dry-cleaning-id");
        $this->shoeCleaningId = $request->request->get("shoe-cleaning-id");
        $this->ironingId = $request->request->get("ironing-id");
        $this->carpetCleaningId = $request->request->get("carpet-cleaning-id");
        $this->sheetCleaningId = $request->request->get("sheet-cleaning-id");
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDryCleaningId(): string
    {
        return $this->dryCleaningId;
    }

    public function getShoeCleaningId(): string
    {
        return $this->shoeCleaningId;
    }

    public function getIroningId(): string
    {
        return $this->ironingId;
    }

    public function getCarpetCleaningId(): string
    {
        return $this->carpetCleaningId;
    }

    public function getSheetCleaningId(): string
    {
        return $this->sheetCleaningId;
    }
}
