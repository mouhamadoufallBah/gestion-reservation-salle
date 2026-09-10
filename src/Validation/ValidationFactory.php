<?php

namespace App\Validation;

class ValidationFactory
{
    public function create(string $validator)
    {
        return match ($validator) {
            'ReservationValidator' => new ReservationValidator(),
            'SalleValidator' => new SalleValidator(),
            'AnnulationReservationValidator' => new AnnulationReservationValidator(),
        };
    }
}
