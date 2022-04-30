<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Repository\ProvinceRepository;

class ProvinceValidator extends ConstraintValidator
{
    private ProvinceRepository $provinceRepository;

    public function __construct(ProvinceRepository $provinceRepository)
    {
        $this->provinceRepository = $provinceRepository;
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
            !is_numeric($value)
            || empty($province = $this->provinceRepository->find($value))
            || $province->getCity()->getId() != $this->context->getRoot()->cityId
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", $province->getName())
                ->addViolation();
        }
    }
}
