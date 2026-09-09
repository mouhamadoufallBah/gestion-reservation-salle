<?php

namespace App\Service;

use App\DTO\ReservationListeDTO;
use App\Repository\ReservationRepositoryInterface;

class ListerReservationsService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}

    /**
     * @param array<string, mixed>|null $criteres
     * @return ReservationListeDTO[]
     */
    public function execute(?array $criteres = null, int $page = 1, int $parPage = 10): array
    {
        if ($criteres === null) {
            $reservations = $this->reservationRepository->lister();
        } else {
            $reservations = $this->reservationRepository->rechercher($criteres, $page, $parPage);
        }

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

    /**
     * @param array<string, mixed> $criteres
     */
    public function compter(array $criteres = []): int
    {
        return $this->reservationRepository->compter($criteres);
    }
}