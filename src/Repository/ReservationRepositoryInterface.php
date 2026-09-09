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

    /**
     * @param array<string, mixed> $criteres
     * @return Reservation[]
     */
    public function rechercher(array $criteres = [], int $page = 1, int $parPage = 10): array;

    /**
     * @param array<string, mixed> $criteres
     */
    public function compter(array $criteres = []): int;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation;

    public function enregistrer(CreerReservationDTO $dto): Reservation;

    public function annuler(int $id): bool;
}