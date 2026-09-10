<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class AnnulationReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (
            !isset($data['id']) ||
            !v::intVal()->positive()->validate($data['id'])
        ) {
            $errors['id'] = "L'identifiant de la réservation doit être un entier positif.";
        }

        return new ValidationResult($data, $errors);
    }
}