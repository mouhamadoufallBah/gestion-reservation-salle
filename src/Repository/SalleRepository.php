<?php

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Builder;

class SalleRepository implements SalleRepositoryInterface
{
    public function __construct(private Capsule $capsule) {}

    public function lister(): array
    {
        return Salle::query()
            ->orderBy('nom')
            ->get()
            ->all();
    }

    public function rechercher(array $criteres = [], int $page = 1, int $parPage = 10): array
    {
        $page = max(1, $page);
        $parPage = max(1, $parPage);
        $offset = ($page - 1) * $parPage;

        return $this->buildQuery($criteres)
            ->orderBy('nom', 'asc')
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
        $query = Salle::query();

        if (!empty($criteres['q'])) {
            $term = '%' . trim((string) $criteres['q']) . '%';
            $query->where(function (Builder $sub) use ($term) {
                $sub->where('nom', 'LIKE', $term)
                    ->orWhere('batiment', 'LIKE', $term);
            });
        }

        if (!empty($criteres['type'])) {
            $typeVal = is_object($criteres['type']) ? $criteres['type']->value : (string) $criteres['type'];
            $query->where('type', $typeVal);
        }

        if (isset($criteres['capacite_min']) && $criteres['capacite_min'] !== '') {
            $query->where('capacite', '>=', (int) $criteres['capacite_min']);
        }

        if (isset($criteres['capacite_max']) && $criteres['capacite_max'] !== '') {
            $query->where('capacite', '<=', (int) $criteres['capacite_max']);
        }

        if (isset($criteres['statut']) && $criteres['statut'] !== '') {
            if ($criteres['statut'] === 'active' || $criteres['statut'] === '1') {
                $query->where('active', true);
            } elseif ($criteres['statut'] === 'inactive' || $criteres['statut'] === '0') {
                $query->where('active', false);
            }
        }

        return $query;
    }

    public function trouver(int $id): ?Salle
    {
        return Salle::find($id);
    }

    public function enregistrer(CreerSalleDTO $dto): Salle
    {
        $salle = new Salle();

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type->value;
        $salle->active = $dto->active;

        $salle->save();

        return $salle;
    }

    public function modifier(ModifierSalleDTO $dto): Salle
    {
        $salle = Salle::find($dto->id);

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type->value;
        $salle->active = $dto->active;

        $salle->save();

        return $salle;
    }
}
