<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Repository\CityRepository;

class CityIdValidator extends ConstraintValidator
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
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
            || empty($city = $this->cityRepository->find($value))
            || $city->getCountry()->getId() != $this->context->getRoot()->getCountryId()
        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", $city->getName())
                ->addViolation();
        }
    }
}
