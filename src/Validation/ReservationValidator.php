<?php

namespace App\Validation;

use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];

        if (
            !isset($data['salle_id']) ||
            !v::intVal()->positive()->validate($data['salle_id'])
        ) {
            $errors['salle_id'] = 'L\'identifiant de la salle doit être un entier positif.';
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
            !isset($data['date_debut']) ||
            !v::date()->validate($data['date_debut'])
        ) {
            $errors['date_debut'] = 'La date de début est invalide.';
        }

        if (
            !isset($data['date_fin']) ||
            !v::date()->validate($data['date_fin'])
        ) {
            $errors['date_fin'] = 'La date de fin est invalide.';
        }

        return new ValidationResult($data, $errors);
    }
}