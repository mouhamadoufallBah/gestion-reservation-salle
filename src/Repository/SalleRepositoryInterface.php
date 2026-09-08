<?php

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\Salle;

interface SalleRepositoryInterface
{
    /**
     * @return Salle[]
     */
    public function lister(): array;

    public function trouver(int $id): ?Salle;

    public function enregistrer(CreerSalleDTO $dto): Salle;

    public function modifier(ModifierSalleDTO $dto): Salle;
}
