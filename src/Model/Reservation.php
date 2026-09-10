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
        'dateDebut',
        'dateFin',
        'statut'
    ];

    protected $casts = [
        'dateDebut' => 'immutable_datetime',
        'dateFin' => 'immutable_datetime',
        'statut' => StatutReservationEnum::class
    ];
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
}
