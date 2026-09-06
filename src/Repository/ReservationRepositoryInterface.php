<?php

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;

interface ReservationRepositoryInterface
{
    /**
     * @return Reservation[]
     */
    public function lister(): array;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation;

    public function enregistrer(CreerReservationDTO $dto): Reservation;

    public function annuler(int $id): bool;
}