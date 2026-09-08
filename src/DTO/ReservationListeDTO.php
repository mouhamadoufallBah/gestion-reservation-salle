<?php

namespace App\DTO;

use App\Model\StatutReservationEnum;

class ReservationListeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly \DateTimeImmutable $dateDebut,
        public readonly \DateTimeImmutable $dateFin,
        public readonly StatutReservationEnum $statut,
    ) {
    }
}