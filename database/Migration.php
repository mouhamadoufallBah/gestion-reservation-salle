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

        echo "Table salles créée avec succès.\n";
    }
}