<?php

namespace App\Service;

use App\DTO\ReservationDetailDTO;
use App\Exception\ReservationIntrouvableException;
use App\Repository\ReservationRepositoryInterface;

class AfficherReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function execute(int $id): ReservationDetailDTO
    {
        $reservation = $this->reservationRepository->trouver($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException(
                "La réservation {$id} n'existe pas."
            );
        }

        return new ReservationDetailDTO(
            id: $reservation->id,
            salleId: $reservation->salle_id,
            responsable: $reservation->responsable,
            email: $reservation->email,
            motif: $reservation->motif,
            dateDebut: $reservation->date_debut,
            dateFin: $reservation->date_fin,
            statut: $reservation->statut,
        );
    }
}