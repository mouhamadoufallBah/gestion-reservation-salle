<?php

namespace App\Model;

enum TypeSalleEnum: string
{
    case COURS = 'cours';
    case INFORMATIQUE = 'informatique';
    case LABORATOIRE = 'laboratoire';
    case AMPHITHEATRE = 'amphitheatre';
    case REUNION = 'reunion';
}
