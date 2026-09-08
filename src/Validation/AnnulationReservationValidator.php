<?php

namespace App\Validation;

class AnnulationReservationValidator
{
    private array $errors = [];

    public function validate(array $data): self
    {
        $this->errors = [];

        if (!isset($data['id']) || $data['id'] === '') {
            $this->errors['id'] = "L'identifiant de la réservation est obligatoire.";

            return $this;
        }

        if (filter_var($data['id'], FILTER_VALIDATE_INT) === false) {
            $this->errors['id'] = "L'identifiant de la réservation doit être un entier.";

            return $this;
        }

        if ((int) $data['id'] <= 0) {
            $this->errors['id'] = "L'identifiant de la réservation doit être supérieur à 0.";
        }

        return $this;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}