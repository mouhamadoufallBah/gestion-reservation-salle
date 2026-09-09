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

    /**
     * @param array<string, mixed> $criteres
     * @return Salle[]
     */
    public function rechercher(array $criteres = [], int $page = 1, int $parPage = 10): array;

    /**
     * @param array<string, mixed> $criteres
     */
    public function compter(array $criteres = []): int;

    public function trouver(int $id): ?Salle;

    public function enregistrer(CreerSalleDTO $dto): Salle;

    public function modifier(ModifierSalleDTO $dto): Salle;
}
