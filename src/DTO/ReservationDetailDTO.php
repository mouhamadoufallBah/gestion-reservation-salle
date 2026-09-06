<?php

namespace App\DTO;

use DateTimeImmutable;

class ReservationDetailDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin,
    ) {
    }
}