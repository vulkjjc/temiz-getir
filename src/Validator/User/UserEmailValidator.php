<?php

namespace App\Validator\User;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Repository\UserRepository;

class UserEmailValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || "" === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, "string");
        }

        if (
            !filter_var($value, FILTER_VALIDATE_EMAIL)
            || empty($this->userRepository->findBy(["email" => $value]))
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", $value)
                ->addViolation();
        }
    }
}
