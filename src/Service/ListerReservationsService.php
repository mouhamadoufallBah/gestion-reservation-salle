<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\ReservationListeDTO;
use App\DTO\ReservationListeDTOBuilder;
use App\Repository\ReservationRepositoryInterface;

final class ListerReservationsService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository
    ) {}


    public function execute(
        ?array $criteres = null,
        int $page = 1,
        int $parPage = 10
    ): array {
        $reservations = $criteres === null
            ? $this->reservationRepository->lister()
            : $this->reservationRepository->rechercher(
                $criteres,
                $page,
                $parPage
            );

        return array_map(
            function ($reservation): ReservationListeDTO {
                return (new ReservationListeDTOBuilder())
                    ->fromModel($reservation)
                    ->build();
            },
            $reservations
        );
    }


    public function compter(array $criteres = []): int
    {
        return $this->reservationRepository->compter($criteres);
    }
}
