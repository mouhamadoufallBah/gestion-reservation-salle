<?php

namespace App\DTO;

use App\Model\TypeSalleEnum;
use InvalidArgumentException;

class ModifierSalleDTOBuilder
{
    private int $id = 0;
    private string $nom = '';
    private string $batiment = '';
    private int $capacite = 0;
    private ?TypeSalleEnum $type = null;
    private bool $active = true;

    public function id(int $id): self
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("L'identifiant de la salle doit être supérieur à zéro.");
        }
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

    public function isActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }

    public function build(): ModifierSalleDTO
    {
        if ($this->id <= 0) {
            throw new InvalidArgumentException("L'identifiant de la salle est obligatoire pour la modification.");
        }

        if ($this->type === null) {
            throw new InvalidArgumentException("Le type de salle est obligatoire pour la modification.");
        }

        return new ModifierSalleDTO(
            id: $this->id,
            nom: $this->nom,
            batiment: $this->batiment,
            capacite: $this->capacite,
            type: $this->type,
            active: $this->active
        );
    }
}