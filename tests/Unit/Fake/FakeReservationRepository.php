<?php

namespace Tests\Unit\Fake;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

class FakeReservationRepository implements ReservationRepositoryInterface
{
    /**
     * @var Reservation[]
     */
    private array $reservations = [];

    private bool $conflit = false;

    public function lister(): array
    {
        return $this->reservations;
    }

    public function trouver(int $id): ?Reservation
    {
        return null;
    }

    public function rechercherConflit(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation {
        if ($this->conflit) {
            return new Reservation();
        }

        return null;
    }

    public function enregistrer(
        CreerReservationDTO $dto
    ): Reservation {
        $reservation = new Reservation();

        $this->reservations[] = $reservation;

        return $reservation;
    }

    public function annuler(int $id): bool
    {
        throw new \LogicException(
            'annuler() n\'est pas utilisé dans ces tests.'
        );
    }

    public function definirConflit(bool $conflit): void
    {
        $this->conflit = $conflit;
    }
}