<?php

namespace App\Service;

use App\DTO\ModifierSalleDTO;
use App\Exception\SalleIntrouvableException;
use App\Repository\SalleRepositoryInterface;

class ModifierSalleService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository
    ) {
    }

    public function execute(ModifierSalleDTO $dto): void
    {
        $salle = $this->salleRepository->trouver($dto->id);

        if ($salle === null) {
            throw new SalleIntrouvableException(
                "La salle {$dto->id} n'existe pas."
            );
        }

        $this->salleRepository->modifier($dto);
    }
}