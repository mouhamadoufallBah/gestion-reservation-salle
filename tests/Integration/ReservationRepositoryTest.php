<?php

namespace Tests\Integration;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Model\Salle;
use App\Model\StatutReservationEnum;
use App\Model\TypeSalleEnum;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;

class ReservationRepositoryTest extends IntegrationTestCase
{
    private ReservationRepository $repository;

    private SalleRepository $salleRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ReservationRepository(
            $this->capsule
        );

        $this->salleRepository = new SalleRepository(
            $this->capsule
        );
    }

    private function creerSalle(): Salle
    {
        return $this->salleRepository->enregistrer(
            new \App\DTO\CreerSalleDTO(
                nom: 'Salle B12',
                batiment: 'B',
                capacite: 40,
                type: TypeSalleEnum::cases()[0],
                active: true
            )
        );
    }

    public function testEnregistrerUneReservation(): void
    {
        $salle = $this->creerSalle();

        $dto = new CreerReservationDTO(
            salleId: $salle->id,
            responsable: 'Mouhamadou',
            email: 'test@example.com',
            motif: 'Réunion',
            dateDebut: new \DateTimeImmutable('+1 day 10:00'),
            dateFin: new \DateTimeImmutable('+1 day 12:00')
        );

        $reservation = $this->repository->enregistrer(
            $dto
        );

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );

        $this->assertNotNull(
            $reservation->id
        );

        $this->assertSame(
            $salle->id,
            $reservation->salle_id
        );

        $this->assertSame(
            'Mouhamadou',
            $reservation->responsable
        );

        $this->assertSame(
            'test@example.com',
            $reservation->email
        );

        $this->assertSame(
            'Réunion',
            $reservation->motif
        );
    }

    public function testTrouverUneReservation(): void
    {
        $salle = $this->creerSalle();

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $resultat = $this->repository->trouver(
            $reservation->id
        );

        $this->assertInstanceOf(
            Reservation::class,
            $resultat
        );

        $this->assertSame(
            $reservation->id,
            $resultat->id
        );
    }

    public function testListerLesReservations(): void
    {
        $salle = $this->creerSalle();

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test1@example.com',
            'motif' => 'Réunion 1',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Ali',
            'email' => 'test2@example.com',
            'motif' => 'Réunion 2',
            'date_debut' => new \DateTimeImmutable('+2 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+2 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $reservations = $this->repository->lister();

        $this->assertCount(
            2,
            $reservations
        );

        $this->assertContainsOnlyInstancesOf(
            Reservation::class,
            $reservations
        );
    }

    public function testRechercherConflit(): void
    {
        $salle = $this->creerSalle();

        $reservationExistante = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $conflit = $this->repository->rechercherConflit(
            $salle->id,
            new \DateTimeImmutable('+1 day 11:00'),
            new \DateTimeImmutable('+1 day 13:00')
        );

        $this->assertInstanceOf(
            Reservation::class,
            $conflit
        );

        $this->assertSame(
            $reservationExistante->id,
            $conflit->id
        );
    }

    public function testAucunConflitSiLesHorairesNeSeChevauchentPas(): void
    {
        $salle = $this->creerSalle();

        Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $conflit = $this->repository->rechercherConflit(
            $salle->id,
            new \DateTimeImmutable('+1 day 13:00'),
            new \DateTimeImmutable('+1 day 15:00')
        );

        $this->assertNull(
            $conflit
        );
    }

    public function testAnnulerUneReservation(): void
    {
        $salle = $this->creerSalle();

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $resultat = $this->repository->annuler(
            $reservation->id
        );

        $this->assertTrue(
            $resultat
        );

        $reservation->refresh();

        $this->assertSame(
            StatutReservationEnum::ANNULEE,
            $reservation->statut
        );
    }

    public function testAnnulerUneReservationInexistante(): void
    {
        $resultat = $this->repository->annuler(999);

        $this->assertFalse(
            $resultat
        );
    }

    public function testRelationReservationSalle(): void
    {
        $salle = $this->creerSalle();

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Mouhamadou',
            'email' => 'test@example.com',
            'motif' => 'Réunion',
            'date_debut' => new \DateTimeImmutable('+1 day 10:00'),
            'date_fin' => new \DateTimeImmutable('+1 day 12:00'),
            'statut' => StatutReservationEnum::CONFIRMEE->value,
        ]);

        $reservation->load('salle');

        $this->assertInstanceOf(
            Salle::class,
            $reservation->salle
        );

        $this->assertSame(
            $salle->id,
            $reservation->salle->id
        );
    }
}