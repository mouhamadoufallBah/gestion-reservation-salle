<?php

namespace Tests\Integration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase;

abstract class IntegrationTestCase extends TestCase
{
    protected Capsule $capsule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->capsule = new Capsule();

        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $this->capsule->setAsGlobal();

        $this->capsule->bootEloquent();

        $this->creerTables();
    }

    protected function creerTables(): void
    {
        $schema = $this->capsule->schema();

        /*
         * Table salles
         */
        $schema->create('salles', function (Blueprint $table) {

            $table->id();

            $table->string('nom');

            $table->string('batiment');

            $table->integer('capacite');

            $table->string('type');

            $table->boolean('active')->default(true);

            $table->timestamps();
        });

        /*
         * Table reservations
         */
        $schema->create('reservations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('salle_id')
                ->constrained('salles')
                ->cascadeOnDelete();

            $table->string('responsable');

            $table->string('email');

            $table->string('motif');

            $table->dateTime('dateDebut');

            $table->dateTime('dateFin');
            
            $table->string('statut')->default('confirmée');;

            $table->timestamps();
        });
    }
}
