<?php

namespace App\DTO;

use App\Model\TypeSalleEnum;

class ModifierSalleDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $batiment,
        public readonly int $capacite,
        public readonly TypeSalleEnum $type,
        public readonly bool $active,
    ) {
    }
}