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

    public function rechercher(array $criteres = [], int $page = 1, int $parPage = 10): array
    {
        $all = $this->filtrer($criteres);
        $offset = max(0, ($page - 1) * $parPage);

        return array_slice($all, $offset, $parPage);
    }

    public function compter(array $criteres = []): int
    {
        return count($this->filtrer($criteres));
    }

    private function filtrer(array $criteres): array
    {
        $result = $this->reservations;

        if (!empty($criteres['salle_id'])) {
            $salleId = (int) $criteres['salle_id'];
            $result = array_filter($result, fn($r) => ($r->salle_id ?? 0) === $salleId);
        }

        if (!empty($criteres['responsable'])) {
            $resp = mb_strtolower((string) $criteres['responsable']);
            $result = array_filter($result, function ($r) use ($resp) {
                return str_contains(mb_strtolower($r->responsable ?? ''), $resp)
                    || str_contains(mb_strtolower($r->email ?? ''), $resp);
            });
        }

        if (!empty($criteres['motif'])) {
            $m = mb_strtolower((string) $criteres['motif']);
            $result = array_filter($result, fn($r) => str_contains(mb_strtolower($r->motif ?? ''), $m));
        }

        return array_values($result);
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
        $reservation->salle_id = $dto->salleId;
        $reservation->responsable = $dto->responsable;
        $reservation->email = $dto->email;
        $reservation->motif = $dto->motif;
        $reservation->dateDebut = $dto->dateDebut;
        $reservation->dateFin = $dto->dateFin;

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