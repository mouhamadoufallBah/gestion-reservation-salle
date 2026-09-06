<?php

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Model\StatutReservationEnum;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function lister(): array
    {
        return Reservation::query()
            ->orderBy('date_debut')
            ->get()
            ->all();
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->first();
    }

    public function enregistrer(CreerReservationDTO $dto): Reservation
    {
        $reservation = new Reservation();

        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->date_debut = $dto->dateDebut;
        $reservation->date_fin = $dto->dateFin;

        $reservation->save();

        return $reservation;
    }

    public function annuler(int $id): bool
    {
        $reservation = $this->trouver($id);

        if ($reservation === null) {
            return false;
        }

        $reservation->statut = StatutReservationEnum::ANNULEE->value;

        return $reservation->save();
    }
}
