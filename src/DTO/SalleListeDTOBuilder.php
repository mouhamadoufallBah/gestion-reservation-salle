<?php

declare(strict_types=1);

namespace App\DTO;

use App\Model\TypeSalleEnum;
use InvalidArgumentException;

class SalleListeDTOBuilder
{
    private int $id = 0;
    private string $nom = '';
    private string $batiment = '';
    private int $capacite = 0;
    private ?TypeSalleEnum $type = null;
    private bool $active = true;

    public function id(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function nom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function batiment(string $batiment): self
    {
        $this->batiment = $batiment;
        return $this;
    }

    public function capacite(int $capacite): self
    {
        $this->capacite = $capacite;
        return $this;
    }

    public function type(TypeSalleEnum $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function active(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function build(): SalleListeDTO
    {
        if ($this->type === null) {
            throw new InvalidArgumentException("Le type de salle est obligatoire.");
        }

        return new SalleListeDTO(
            id: $this->id,
            nom: $this->nom,
            batiment: $this->batiment,
            capacite: $this->capacite,
            type: $this->type,
            active: $this->active
        );
    }
}