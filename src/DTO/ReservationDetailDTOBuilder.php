<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\StatutReservationEnum;
use DateTimeImmutable;
use InvalidArgumentException;

class ReservationDetailDTOBuilder
{
    private int $id = 0;
    private int $salleId = 0;
    private string $responsable = '';
    private string $email = '';
    private string $motif = '';
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;
    private ?StatutReservationEnum $statut = null;

    public function id(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function salleId(int $salleId): self
    {
        $this->salleId = $salleId;
        return $this;
    }

    public function responsable(string $responsable): self
    {
        $this->responsable = $responsable;
        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function motif(string $motif): self
    {
        $this->motif = $motif;
        return $this;
    }

    public function dateDebut(DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function dateFin(DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function statut(StatutReservationEnum $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function build(): ReservationDetailDTO
    {
        if ($this->dateDebut === null || $this->dateFin === null) {
            throw new InvalidArgumentException("Les dates de début et de fin sont obligatoires.");
        }

        if ($this->statut === null) {
            throw new InvalidArgumentException("Le statut de la réservation est obligatoire.");
        }

        return new ReservationDetailDTO(
            id: $this->id,
            salleId: $this->salleId,
            responsable: $this->responsable,
            email: $this->email,
            motif: $this->motif,
            dateDebut: $this->dateDebut,
            dateFin: $this->dateFin,
            statut: $this->statut
        );
    }
}