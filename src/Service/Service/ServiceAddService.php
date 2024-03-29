<?php

namespace App\Service\Service;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Repository\ServiceRepository;
use App\Service\Violation\ViolationService;
use App\DTO\Service\ServiceAddRequestDTO;
use App\Entity\Service;

class ServiceAddService
{
    private ServiceRepository $serviceRepository;
    private ViolationService $violationService;

    public function __construct(ServiceRepository $serviceRepository, ViolationService $violationService) 
    {
        $this->serviceRepository = $serviceRepository;
        $this->violationService = $violationService;
    }

    public function attemptToAddService(ServiceAddRequestDTO $serviceAddRequestDTO): Service
    {
        $service = $this->setServiceAddProperties($serviceAddRequestDTO, new Service());

        if ($violation = $this->violationService->getLastViolation($service)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->serviceRepository->add($service, true);

        return $service;
    }

    private function setServiceAddProperties(
        ServiceAddRequestDTO $serviceAddRequestDTO,
        Service $service
    ): Service {
        return $service;
    }
}
