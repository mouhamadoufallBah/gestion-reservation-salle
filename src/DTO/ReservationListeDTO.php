<?php

namespace App\DTO;

use DateTimeImmutable;

class ReservationListeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $responsable,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin,
    ) {
    }
}