<?php

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;



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
