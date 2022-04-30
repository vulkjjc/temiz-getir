<?php

namespace App\Validator\Location;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use App\Repository\CountryRepository;
use App\Repository\CityRepository;

class CountryValidator extends ConstraintValidator
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository, CityRepository $cityRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (null === $value || "" === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, "string");
        }

        if (!is_numeric($value) || empty($country = $this->countryRepository->find($value))) {
            $this->context->buildViolation($constraint->message)
                ->setParameter("{{ string }}", $country->getName())
                ->addViolation();
        }
    }
}