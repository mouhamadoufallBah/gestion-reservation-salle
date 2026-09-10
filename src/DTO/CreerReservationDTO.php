<?php

namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTO
{
    public function __construct(
        public readonly int $salleId,
        public readonly string $responsable,
        public readonly string $email,
        public readonly string $motif,
        public readonly DateTimeImmutable $dateDebut,
        public readonly DateTimeImmutable $dateFin,
    ) {}

    public function toArray(): array
    {
        return [
            'salle_id' => $this->salleId,
            'responsable' => $this->responsable,
            'email' => $this->email,
            'motif' => $this->motif,
            'dateDebut' => $this->dateDebut->format('Y-m-d H:i:s'),
            'dateFin' => $this->dateFin->format('Y-m-d H:i:s'),
        ];
    }
}