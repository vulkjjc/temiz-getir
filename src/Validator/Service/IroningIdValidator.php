<?php

namespace App\Validator\Service;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Repository\IroningRepository;

class IroningIdValidator extends ConstraintValidator
{
    private IroningRepository $ironingRepository;

    public function __construct(IroningRepository $ironingRepository)
    {
        $this->ironingRepository = $ironingRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || "" === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, "string");
        }

        if (!is_numeric($value) || empty($this->ironingRepository->find($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", "Ironing")
                ->addViolation();
        }
    }
}
