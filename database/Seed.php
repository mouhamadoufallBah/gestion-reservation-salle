<?php

namespace Database;

use Illuminate\Database\Capsule\Manager;

use function Illuminate\Support\now;

class Seed
{
    public function run(): void
    {
        $migration = new Migration();
        $migration->run();

        $salles = [
            [
                'nom' => 'Amphithéâtre A',
                'batiment' => 'Bâtiment A',
                'capacite' => 250,
                'type' => 'amphitheatre',
                'active' => true,
            ],
            [
                'nom' => 'Salle B12',
                'batiment' => 'Bâtiment B',
                'capacite' => 40,
                'type' => 'cours',
                'active' => true,
            ],
            [
                'nom' => 'Laboratoire Chimie',
                'batiment' => 'Bâtiment C',
                'capacite' => 24,
                'type' => 'laboratoire',
                'active' => true,
            ],
            [
                'nom' => 'Salle Informatique 1',
                'batiment' => 'Bâtiment D',
                'capacite' => 30,
                'type' => 'informatique',
                'active' => true,
            ],
            [
                'nom' => 'Salle de réunion',
                'batiment' => 'Administration',
                'capacite' => 12,
                'type' => 'reunion',
                'active' => true,
            ],
        ];

        foreach ($salles as $salle) {
            $now = now();

            Manager::table('salles')->updateOrInsert(
                ['nom' => $salle['nom']],
                array_merge($salle, [
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }

        $reservations = [
            [
                'salle_id' => 1,
                'responsable' => 'Mouhamadou Bah',
                'email' => 'mouhamadou@example.com',
                'motif' => 'Cours de programmation PHP',
                'dateDebut' => '2026-09-10 08:00:00',
                'dateFin' => '2026-09-10 12:00:00',
                'statut' => 'annulée',
            ],
            [
                'salle_id' => 2,
                'responsable' => 'Aminata Diop',
                'email' => 'aminata@example.com',
                'motif' => 'Réunion pédagogique',
                'dateDebut' => '2026-09-11 09:00:00',
                'dateFin' => '2026-09-11 11:00:00',
                'statut' => 'confirmée',
            ],
            [
                'salle_id' => 3,
                'responsable' => 'Ibrahima Fall',
                'email' => 'ibrahima@example.com',
                'motif' => 'Travaux pratiques de chimie',
                'dateDebut' => '2026-09-12 14:00:00',
                'dateFin' => '2026-09-12 17:00:00',
                'statut' => 'confirmée',
            ],
        ];

        foreach ($reservations as $reservation) {
            $now = now();

            Manager::table('reservations')->updateOrInsert(
                [
                    'salle_id' => $reservation['salle_id'],
                    'dateDebut' => $reservation['dateDebut'],
                ],
                array_merge($reservation, [
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );
        }

        echo "Les salles et les réservations ont été insérées avec succès.\n";
    }
}