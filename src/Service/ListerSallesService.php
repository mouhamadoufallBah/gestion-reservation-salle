<?php

namespace App\Service;

use App\DTO\SalleListeDTO;
use App\Repository\SalleRepositoryInterface;

class ListerSallesService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository
    ) {}

    /**
     * @param array<string, mixed>|null $criteres
     * @return SalleListeDTO[]
     */
    public function execute(?array $criteres = null, int $page = 1, int $parPage = 10): array
    {
        if ($criteres === null) {
            $salles = $this->salleRepository->lister();
        } else {
            $salles = $this->salleRepository->rechercher($criteres, $page, $parPage);
        }

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

    /**
     * @param array<string, mixed> $criteres
     */
    public function compter(array $criteres = []): int
    {
        return $this->salleRepository->compter($criteres);
    }
}
