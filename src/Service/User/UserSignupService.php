<?php

namespace App\Service\User;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Entity\User;
use App\Entity\Location;
use App\Entity\Phone;
use App\Repository\UserRepository;
use App\Service\Violation\ViolationService;
use App\Service\Location\LocationAddService;
use App\Service\Phone\PhoneAddService;
use App\Interface\DTO\User\UserSignupRequestDTOInterface;
use App\DTO\Location\LocationAddRequestDTO;
use App\DTO\Phone\PhoneAddRequestDTO;

class UserSignupService
{
    private UserPasswordHasherInterface $passwordHasher;

    private UserRepository $userRepository;
    private ViolationService $violationService;
    private LocationAddService $locationAddService;
    private PhoneAddService $phoneAddService;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        ViolationService $violationService,
        LocationAddService $locationAddService,
        PhoneAddService $phoneAddService
    ) {
        $this->passwordHasher = $passwordHasher;

        $this->userRepository = $userRepository;
        $this->violationService = $violationService;
        $this->locationAddService = $locationAddService;
        $this->phoneAddService = $phoneAddService;
    }

    public function attemptToSignupUser(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        LocationAddRequestDTO $locationAddRequestDTO,
        PhoneAddRequestDTO $phoneAddRequestDTO
    ): User {
        $user = $this->setUserProperties(
            $userSignupRequestDTO,
            new User(),
            $this->locationAddService->attemptToAddLocation($locationAddRequestDTO),
            $this->phoneAddService->attemptToAddPhone($phoneAddRequestDTO)
        );

        if ($violation = $this->violationService->getLastViolation($user)) {
            throw new BadRequestHttpException($violation->getMessage());
        }

        $this->userRepository->add($user, true);

        return $user;
    }

    private function setUserProperties(
        UserSignupRequestDTOInterface $userSignupRequestDTO,
        User $user,
        Location $location,
        Phone $phone
    ): User {
        $user->setName($userSignupRequestDTO->getName());
        $user->setEmail($userSignupRequestDTO->getEmail());
        $user->setPhone($phone);
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $userSignupRequestDTO->getPassword()
            )
        );
        $user->setRoles([$userSignupRequestDTO->getUserRole()]);
        $user->setLocation($location);

        return $user;
    }
}
