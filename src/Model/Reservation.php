<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut'
    ];

    protected $casts = [
        'date_debut' => 'immutable_datetime',
        'date_fin' => 'immutable_datetime',
        'statut' => StatutReservationEnum::class
    ];
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
