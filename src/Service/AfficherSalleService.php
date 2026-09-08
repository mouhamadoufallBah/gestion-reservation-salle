<?php

namespace App\Service;

use App\DTO\SalleDetailDTO;
use App\Exception\SalleIntrouvableException;
use App\Repository\SalleRepositoryInterface;

class AfficherSalleService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository
    ) {
    }

    public function execute(int $id): SalleDetailDTO
    {
        $salle = $this->salleRepository->trouver($id);

        if ($salle === null) {
            throw new SalleIntrouvableException(
                "La salle {$id} n'existe pas."
            );
        }

        return new SalleDetailDTO(
            id: $salle->id,
            nom: $salle->nom,
            batiment: $salle->batiment,
            capacite: $salle->capacite,
            type: $salle->type,
            active: $salle->active,
        );
    }
}
