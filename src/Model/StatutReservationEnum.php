<?php
namespace App\Model;

enum StatutReservationEnum: string{
    case CONFIRMEE= 'confirmée';
    case ANNULEE= 'annulée';
}