<?php

namespace App\Service\Phone;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Repository\PhoneRepository;
use App\Entity\Phone;
use App\Service\Violation\ViolationService;
use App\DTO\Phone\PhoneAddRequestDTO;

class PhoneAddService
{
    private ManagerRegistry $doctrine;

    private pHONERepository $phoneRepository;
    private ViolationService $violationService;

    public function __construct(
        ManagerRegistry $doctrine,
        PhoneRepository $phoneRepository,
        ViolationService $violationService,
    ) {
        $this->doctrine = $doctrine;

        $this->phoneRepository = $phoneRepository;
        $this->violationService = $violationService;
    }

    public function attemptToAddPhone(PhoneAddRequestDTO $phoneAddRequestDTO): Phone
    {
        $phone = new Phone();
        $phone = $this->setPhoneAddProperties($phoneAddRequestDTO, $phone);

        if ($violation = $this->violationService->getLastViolation($phone)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->phoneRepository->add($phone, true);

        return $phone;
    }

    private function setPhoneAddProperties(
        PhoneAddRequestDTO $phoneAddRequestDTO,
        Phone $phone
    ): Phone {
        $phone->setPhone($phoneAddRequestDTO->getPhone());
        $phone->setPhoneBusiness($phoneAddRequestDTO->getPhoneBusiness());

        return $phone;
    }
}
