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
                'type' => 'Amphithéâtre',
                'active' => true,
            ],
            [
                'nom' => 'Salle B12',
                'batiment' => 'Bâtiment B',
                'capacite' => 40,
                'type' => 'Salle',
                'active' => true,
            ],
            [
                'nom' => 'Laboratoire Chimie',
                'batiment' => 'Bâtiment C',
                'capacite' => 24,
                'type' => 'Laboratoire',
                'active' => true,
            ],
            [
                'nom' => 'Salle Informatique 1',
                'batiment' => 'Bâtiment D',
                'capacite' => 30,
                'type' => 'Informatique',
                'active' => true,
            ],
            [
                'nom' => 'Salle de réunion',
                'batiment' => 'Administration',
                'capacite' => 12,
                'type' => 'Réunion',
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

        echo "Les salles ont été insérées avec succès.\n";
    }
}
