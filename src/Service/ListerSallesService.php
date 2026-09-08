<?php

namespace App\Service;

use App\DTO\SalleListeDTO;
use App\Repository\SalleRepositoryInterface;

class ListerSallesService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository
    ) {}

    public function execute(): array
    {
        $salles = $this->salleRepository->lister();

        return array_map(
            fn($salle) => new SalleListeDTO(
                id: $salle->id,
                nom: $salle->nom,
                batiment: $salle->batiment,
                capacite: $salle->capacite,
                type: $salle->type,
                active: $salle->active,
            ),
            $salles
        );
    }
}
