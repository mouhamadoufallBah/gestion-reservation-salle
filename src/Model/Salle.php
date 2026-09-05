<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $fillable = [
        'nom',
        'batiment',
        'capacite',
        'type',
        'active',
    ];

    protected $casts = [
        'capacite' => 'integer',
        'active' => 'boolean',
        'type' => TypeSalleEnum::class
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
