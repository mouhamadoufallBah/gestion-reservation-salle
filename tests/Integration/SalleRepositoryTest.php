<?php

namespace Tests\Integration;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\Salle;
use App\Model\TypeSalleEnum;
use App\Repository\SalleRepository;

class SalleRepositoryTest extends IntegrationTestCase
{
    private SalleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new SalleRepository(
            $this->capsule
        );
    }

    public function testEnregistrerUneSalle(): void
    {
        $dto = new CreerSalleDTO(
            nom: 'Salle B12',
            batiment: 'B',
            capacite: 40,
            type: TypeSalleEnum::cases()[0],
            active: true
        );

        $salle = $this->repository->enregistrer($dto);

        $this->assertInstanceOf(
            Salle::class,
            $salle
        );

        $this->assertNotNull(
            $salle->id
        );

        $this->assertSame(
            'Salle B12',
            $salle->nom
        );

        $this->assertSame(
            'B',
            $salle->batiment
        );

        $this->assertSame(
            40,
            $salle->capacite
        );

        $this->assertTrue(
            $salle->active
        );
    }

    public function testTrouverUneSalle(): void
    {
        $dto = new CreerSalleDTO(
            nom: 'Salle B12',
            batiment: 'B',
            capacite: 40,
            type: TypeSalleEnum::cases()[0],
            active: true
        );

        $salleCreee = $this->repository->enregistrer($dto);

        $salle = $this->repository->trouver(
            $salleCreee->id
        );

        $this->assertInstanceOf(
            Salle::class,
            $salle
        );

        $this->assertSame(
            $salleCreee->id,
            $salle->id
        );

        $this->assertSame(
            'Salle B12',
            $salle->nom
        );
    }

    public function testTrouverUneSalleInexistante(): void
    {
        $salle = $this->repository->trouver(999);

        $this->assertNull($salle);
    }

    public function testListerLesSalles(): void
    {
        $this->repository->enregistrer(
            new CreerSalleDTO(
                nom: 'Salle B12',
                batiment: 'B',
                capacite: 40,
                type: TypeSalleEnum::cases()[0],
                active: true
            )
        );

        $this->repository->enregistrer(
            new CreerSalleDTO(
                nom: 'Amphithéâtre A',
                batiment: 'A',
                capacite: 250,
                type: TypeSalleEnum::cases()[0],
                active: true
            )
        );

        $salles = $this->repository->lister();

        $this->assertCount(
            2,
            $salles
        );

        $this->assertContainsOnlyInstancesOf(
            Salle::class,
            $salles
        );
    }

    public function testModifierUneSalle(): void
    {
        $salle = $this->repository->enregistrer(
            new CreerSalleDTO(
                nom: 'Salle B12',
                batiment: 'B',
                capacite: 40,
                type: TypeSalleEnum::cases()[0],
                active: true
            )
        );

        $dto = new ModifierSalleDTO(
            id: $salle->id,
            nom: 'Salle B15',
            batiment: 'C',
            capacite: 50,
            type: TypeSalleEnum::cases()[0],
            active: false
        );

        $salleModifiee = $this->repository->modifier($dto);

        $this->assertSame(
            $salle->id,
            $salleModifiee->id
        );

        $this->assertSame(
            'Salle B15',
            $salleModifiee->nom
        );

        $this->assertSame(
            'C',
            $salleModifiee->batiment
        );

        $this->assertSame(
            50,
            $salleModifiee->capacite
        );

        $this->assertFalse(
            $salleModifiee->active
        );
    }

    public function testRelationSalleReservations(): void
    {
        $salle = $this->repository->enregistrer(
            new CreerSalleDTO(
                nom: 'Salle B12',
                batiment: 'B',
                capacite: 40,
                type: TypeSalleEnum::cases()[0],
                active: true
            )
        );

        $salle->reservations()->create([
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'dateDebut' => new \DateTimeImmutable('+1 day 10:00'),
            'dateFin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => 'confirmée',
        ]);

        $salle->load('reservations');

        $this->assertCount(
            1,
            $salle->reservations
        );

        $this->assertSame(
            $salle->id,
            $salle->reservations->first()->salle_id
        );
    }
}