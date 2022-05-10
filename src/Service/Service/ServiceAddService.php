<?php

namespace App\Service\Service;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use App\Service\Violation\ViolationService;
use App\DTO\Service\ServiceAddRequestDTO;
use App\Entity\Service;

class ServiceAddService
{
    private ManagerRegistry $doctrine;

    private ViolationService $violationService;

    public function __construct(ManagerRegistry $doctrine, ViolationService $violationService) 
    {
        $this->doctrine = $doctrine;

        $this->violationService = $violationService;
    }

    public function attemptToAddService(ServiceAddRequestDTO $serviceAddRequestDTO): Service
    {
        $service = new Service();
        $service = $this->setServiceAddProperties($serviceAddRequestDTO, $service);

        if ($violation = $this->violationService->getLastViolation($service)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->addService($service);

        return $service;
    }

    private function addService(Service $service)
    {
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($service);
        $entityManager->flush();
    }

    private function setServiceAddProperties(
        ServiceAddRequestDTO $serviceAddRequestDTO,
        Service $service
    ): Service {
        return $service;
    }
}
