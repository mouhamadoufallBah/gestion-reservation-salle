<?php

namespace App\DTO;

class AnnulerReservationDTO
{
    public function __construct(
        public readonly int $id,
    ) {
    }
}