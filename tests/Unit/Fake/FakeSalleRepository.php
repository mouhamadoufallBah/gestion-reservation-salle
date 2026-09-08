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