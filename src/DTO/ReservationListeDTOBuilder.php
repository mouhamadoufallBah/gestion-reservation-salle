<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\Reservation;
use App\Model\StatutReservationEnum;
use DateTimeImmutable;
use InvalidArgumentException;

final class ReservationListeDTOBuilder
{
    private ?int $id = null;
    private ?int $salleId = null;
    private ?string $nomSalle = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
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

    public function nomSalle(string $nomSalle): self
    {
        $this->nomSalle = $nomSalle;

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

    public function fromModel(Reservation $reservation): self
    {
        return $this
            ->id($reservation->id)
            ->salleId($reservation->salle_id)
            ->nomSalle($reservation->salle->nom)
            ->responsable($reservation->responsable)
            ->email($reservation->email)
            ->motif($reservation->motif)
            ->dateDebut($reservation->dateDebut)
            ->dateFin($reservation->dateFin)
            ->statut($reservation->statut);
    }

    public function build(): ReservationListeDTO
    {
        if (
            $this->id === null ||
            $this->salleId === null ||
            $this->nomSalle === null ||
            $this->responsable === null ||
            $this->email === null ||
            $this->motif === null ||
            $this->dateDebut === null ||
            $this->dateFin === null ||
            $this->statut === null
        ) {
            throw new InvalidArgumentException(
                'Impossible de construire le DTO de réservation : des données sont manquantes.'
            );
        }

        return new ReservationListeDTO(
            id: $this->id,
            salleId: $this->salleId,
            nomSalle: $this->nomSalle,
            responsable: $this->responsable,
            email: $this->email,
            motif: $this->motif,
            dateDebut: $this->dateDebut,
            dateFin: $this->dateFin,
            statut: $this->statut,
        );
    }
}