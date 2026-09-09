<?php

namespace Tests\Unit\Fake;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

class FakeSalleRepository implements SalleRepositoryInterface
{
    /**
     * @var Salle[]
     */
    private array $salles = [];

    public function ajouter(Salle $salle): void
    {
        $this->salles[$salle->id] = $salle;
    }

    public function lister(): array
    {
        return array_values($this->salles);
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
        $result = array_values($this->salles);

        if (!empty($criteres['q'])) {
            $q = mb_strtolower((string) $criteres['q']);
            $result = array_filter($result, function ($s) use ($q) {
                return str_contains(mb_strtolower($s->nom ?? ''), $q)
                    || str_contains(mb_strtolower($s->batiment ?? ''), $q);
            });
        }

        if (!empty($criteres['type'])) {
            $t = is_object($criteres['type']) ? $criteres['type']->value : (string) $criteres['type'];
            $result = array_filter($result, function ($s) use ($t) {
                $typeVal = is_object($s->type) ? $s->type->value : (string) $s->type;
                return $typeVal === $t;
            });
        }

        if (isset($criteres['capacite_min']) && $criteres['capacite_min'] !== '') {
            $min = (int) $criteres['capacite_min'];
            $result = array_filter($result, fn($s) => ($s->capacite ?? 0) >= $min);
        }

        if (isset($criteres['statut']) && $criteres['statut'] !== '') {
            $active = $criteres['statut'] === 'active' || $criteres['statut'] === '1';
            $result = array_filter($result, fn($s) => (bool) ($s->active ?? false) === $active);
        }

        return array_values($result);
    }

    public function trouver(int $id): ?Salle
    {
        return $this->salles[$id] ?? null;
    }

    public function enregistrer(CreerSalleDTO $dto): Salle
    {
        $salle = new Salle();

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;

        $salle->id = count($this->salles) + 1;

        $this->salles[$salle->id] = $salle;

        return $salle;
    }

    public function modifier(ModifierSalleDTO $dto): Salle
    {
        throw new \LogicException(
            'modifier() n\'est pas utilisé dans ces tests.'
        );
    }
}