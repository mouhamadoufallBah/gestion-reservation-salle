<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (
            !isset($data['salleId']) ||
            !v::intVal()->positive()->validate($data['salleId'])
        ) {
            $errors['salleId'] = 'L\'identifiant de la salle doit être un entier positif.';
        }

        if (
            !isset($data['responsable']) ||
            !v::stringType()->length(2, 120)->validate($data['responsable'])
        ) {
            $errors['responsable'] = 'Le responsable doit contenir entre 2 et 120 caractères.';
        }

        if (
            !isset($data['email']) ||
            !v::email()->validate($data['email'])
        ) {
            $errors['email'] = 'L\'adresse email est invalide.';
        }

        if (
            !isset($data['motif']) ||
            !v::stringType()->length(5, 255)->validate($data['motif'])
        ) {
            $errors['motif'] = 'Le motif doit contenir entre 5 et 255 caractères.';
        }

        if (
            !isset($data['dateDebut']) ||
            !v::dateTime('Y-m-d\TH:i')->validate($data['dateDebut'])
        ) {
            $errors['dateDebut'] = 'La date de début est invalide.';
        }

        if (
            !isset($data['dateFin']) ||
            !v::dateTime('Y-m-d\TH:i')->validate($data['dateFin'])
        ) {
            $errors['dateFin'] = 'La date de fin est invalide.';
        }

        return new ValidationResult($data, $errors);
    }
}
