<?php

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\StatutReservationEnum;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Builder;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function __construct(Capsule $capsule) {}

    public function lister(): array
    {
        return Reservation::query()
            ->orderBy('dateDebut', 'desc')
            ->get()
            ->all();
    }

    public function rechercher(array $criteres = [], int $page = 1, int $parPage = 10): array
    {
        $page = max(1, $page);
        $parPage = max(1, $parPage);
        $offset = ($page - 1) * $parPage;

        return $this->buildQuery($criteres)
            ->orderBy('dateDebut', 'desc')
            ->offset($offset)
            ->limit($parPage)
            ->get()
            ->all();
    }

    public function compter(array $criteres = []): int
    {
        return $this->buildQuery($criteres)->count();
    }

    private function buildQuery(array $criteres): Builder
    {
        $query = Reservation::query();

        if (!empty($criteres['salle_id'])) {
            $query->where('salle_id', (int) $criteres['salle_id']);
        }

        if (!empty($criteres['responsable'])) {
            $term = '%' . trim((string) $criteres['responsable']) . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('responsable', 'LIKE', $term)
                    ->orWhere('email', 'LIKE', $term);
            });
        }

        if (!empty($criteres['motif'])) {
            $term = '%' . trim((string) $criteres['motif']) . '%';
            $query->where('motif', 'LIKE', $term);
        }

        if (!empty($criteres['dateDebut'])) {
            $query->where('dateDebut', '>=', $criteres['dateDebut']);
        }

        if (!empty($criteres['dateFin'])) {
            $query->where('dateFin', '<=', $criteres['dateFin']);
        }

        if (!empty($criteres['statut'])) {
            $statutVal = is_object($criteres['statut']) ? $criteres['statut']->value : (string) $criteres['statut'];
            $query->where('statut', $statutVal);
        }

        return $query;
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
            ->where('statut', '!=', StatutReservationEnum::ANNULEE->value)
            ->where('dateDebut', '<', $dateFin)
            ->where('dateFin', '>', $dateDebut)
            ->first();
    }

    public function enregistrer(CreerReservationDTO $dto): Reservation
    {
        $reservation = new Reservation();
        return $reservation::resolveConnection()->transaction(function () use ($dto) {
            $conflit = $this->rechercherConflit(
                $dto->salleId,
                $dto->dateDebut,
                $dto->dateFin
            );

            if ($conflit !== null) {
                throw new SalleIndisponibleException(
                    'La salle est déjà réservée sur cette période.'
                );
            }

            $reservation = new Reservation();

            $reservation->salle_id = $dto->salleId;
            $reservation->responsable = $dto->responsable;
            $reservation->email = $dto->email;
            $reservation->motif = $dto->motif;
            $reservation->dateDebut = $dto->dateDebut;
            $reservation->dateFin = $dto->dateFin;
            $reservation->statut = StatutReservationEnum::CONFIRMEE->value;

            $reservation->save();

            return $reservation;
        });
    }

    public function annuler(int $id): bool
    {
        $reservation = new Reservation();
        return (bool) $reservation::resolveConnection()->transaction(function () use ($id) {
            $reservation = $this->trouver($id);

            if ($reservation === null) {
                return false;
            }

            $reservation->statut = StatutReservationEnum::ANNULEE->value;

            return $reservation->save();
        });
    }
}
