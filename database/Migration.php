<?php

namespace Database;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

class Migration
{
    public function run(): void
    {
        if (Manager::schema()->hasTable('salles')) {
            echo "La table salles existe déjà.\n";
            return;
        }

        Manager::schema()->create('salles', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('batiment');
            $table->integer('capacite');
            $table->string('type');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Manager::schema()->create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('salle_id')
                ->constrained('salles')
                ->cascadeOnDelete();

            $table->string('responsable');
            $table->string('email');
            $table->string('motif');

            $table->dateTime('date_debut');
            $table->dateTime('date_fin');

            $table->string('statut')->default('en_attente');

            $table->timestamps();
        });

        echo "Table salles créée avec succès.\n";
    }

    public function down(): void
    {
        if (Manager::schema()->hasTable('reservations')) {
            Manager::schema()->drop('reservations');
            echo "Table reservations supprimée.\n";
        }

        if (Manager::schema()->hasTable('salles')) {
            Manager::schema()->drop('salles');
            echo "Table salles supprimée.\n";
        }
    }
}
