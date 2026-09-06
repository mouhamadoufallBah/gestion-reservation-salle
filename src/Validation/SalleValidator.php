<?php

namespace App\Validation;

use App\Model\TypeSalleEnum;
use Respect\Validation\Validator as v;

class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (!isset($data['nom']) || !v::stringType()->length(2, 100)->validate($data['nom'])) {
            $errors['nom'] = 'Le nom est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        if (!isset($data['batiment']) || !v::stringType()->length(2, 100)->validate($data['batiment'])) {
            $errors['batiment'] = 'Le bâtiment est obligatoire et doit contenir entre 2 et 100 caractères.';
        }

        if (
            !isset($data['capacite']) ||
            !v::intVal()->between(1, 1000)->validate($data['capacite'])
        ) {
            $errors['capacite'] = 'La capacité doit être un entier compris entre 1 et 1000.';
        }

        if (
            !isset($data['type']) ||
            TypeSalleEnum::tryFrom($data['type']) === null
        ) {
            $errors['type'] = 'Le type de salle est invalide.';
        }

        if (
            !isset($data['active']) ||
            !v::boolVal()->validate($data['active'])
        ) {
            $errors['active'] = 'Le champ active doit être un booléen.';
        }

        return new ValidationResult($data, $errors);
    }
}
