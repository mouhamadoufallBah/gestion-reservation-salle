<?php

namespace App\DTO;

use App\Model\TypeSalleEnum;

class CreerSalleDTO
{
    public function __construct(
        public readonly string $nom,
        public readonly string $batiment,
        public readonly int $capacite,
        public readonly TypeSalleEnum $type,
        public readonly bool $active,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['nom'],
            $data['batiment'],
            $data['capacite'],
            $data['type'],
            $data['active']
        );
    }
}
