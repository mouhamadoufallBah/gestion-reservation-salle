<?php

namespace App\Service;

use App\DTO\CreerSalleDTO;
use App\Repository\SalleRepository;

class CreerSalleService
{
    public function __construct(
        private SalleRepository $salleRepository
    ) {
    }

    public function execute(CreerSalleDTO $dto): void
    {
        $this->salleRepository->enregistrer($dto);
    }
}