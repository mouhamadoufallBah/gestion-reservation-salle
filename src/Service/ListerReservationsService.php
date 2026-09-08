<?php

namespace App\Service;

use App\DTO\ReservationListeDTO;
use App\Repository\ReservationRepositoryInterface;

class ListerReservationsService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function execute(): array
    {
        $reservations = $this->reservationRepository->lister();

        return array_map(
            fn ($reservation) => new ReservationListeDTO(
                id: $reservation->id,
                salleId: $reservation->salle_id,
                responsable: $reservation->responsable,
                email: $reservation->email,
                motif: $reservation->motif,
                dateDebut: $reservation->date_debut,
                dateFin: $reservation->date_fin,
                statut: $reservation->statut,
            ),
            $reservations
        );
    }
}