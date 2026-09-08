<?php

namespace Tests\Unit\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
use App\Service\CreerReservationService;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Fake\FakeReservationRepository;
use Tests\Unit\Fake\FakeSalleRepository;

class CreerReservationServiceTest extends TestCase
{
    private FakeSalleRepository $salleRepository;

    private FakeReservationRepository $reservationRepository;

    private CreerReservationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->salleRepository = new FakeSalleRepository();

        $this->reservationRepository =
            new FakeReservationRepository();

        $this->service = new CreerReservationService(
            $this->salleRepository,
            $this->reservationRepository
        );
    }

    public function testReservationValide(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        $dto = $this->creerDTO(
            '+1 day 10:00',
            '+1 day 12:00'
        );

        $reservation = $this->service->execute($dto);

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );

        $this->assertCount(
            1,
            $this->reservationRepository->lister()
        );
    }

    public function testSalleInexistante(): void
    {
        $dto = $this->creerDTO(
            '+1 day 10:00',
            '+1 day 12:00',
            999
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La salle demandée n\'existe pas.'
        );

        $this->service->execute($dto);
    }

    public function testSalleInactive(): void
    {
        $salle = $this->creerSalleInactive();

        $this->salleRepository->ajouter($salle);

        $dto = $this->creerDTO(
            '+1 day 10:00',
            '+1 day 12:00'
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La salle est inactive.'
        );

        $this->service->execute($dto);
    }

    public function testDateFinAvantDateDebut(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        $dto = $this->creerDTO(
            '+1 day 14:00',
            '+1 day 10:00'
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La date de début doit précéder la date de fin.'
        );

        $this->service->execute($dto);
    }

    public function testDureeSuperieureAQuatreHeures(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        $dto = $this->creerDTO(
            '+1 day 10:00',
            '+1 day 15:00'
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La durée de réservation ne peut pas dépasser quatre heures.'
        );

        $this->service->execute($dto);
    }

    public function testDatePassee(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        $dto = $this->creerDTO(
            '-1 day 10:00',
            '-1 day 12:00'
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La réservation doit commencer dans le futur.'
        );

        $this->service->execute($dto);
    }

    public function testConflitAvecUneReservation(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        /*
         * On indique simplement au Fake :
         * "une réservation existe déjà".
         */
        $this->reservationRepository->definirConflit(true);

        $dto = $this->creerDTO(
            '+1 day 11:00',
            '+1 day 13:00'
        );

        $this->expectException(
            SalleIndisponibleException::class
        );

        $this->expectExceptionMessage(
            'La salle est déjà réservée sur cette période.'
        );

        $this->service->execute($dto);
    }

    public function testReservationAdjacenteSansChevauchement(): void
    {
        $salle = $this->creerSalleActive();

        $this->salleRepository->ajouter($salle);

        /*
         * Aucun conflit.
         */
        $this->reservationRepository->definirConflit(false);

        $dto = $this->creerDTO(
            '+1 day 12:00',
            '+1 day 14:00'
        );

        $reservation = $this->service->execute($dto);

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );

        $this->assertCount(
            1,
            $this->reservationRepository->lister()
        );
    }

    private function creerSalleActive(): Salle
    {
        $salle = new Salle();

        $salle->id = 1;
        $salle->nom = 'Salle B12';
        $salle->batiment = 'B';
        $salle->capacite = 40;
        $salle->active = true;

        return $salle;
    }

    private function creerSalleInactive(): Salle
    {
        $salle = $this->creerSalleActive();

        $salle->active = false;

        return $salle;
    }

    private function creerDTO(
        string $dateDebut,
        string $dateFin,
        int $salleId = 1
    ): CreerReservationDTO {
        return new CreerReservationDTO(
            salleId: $salleId,
            responsable: 'Mouhamadou',
            email: 'test@example.com',
            motif: 'Réunion',
            dateDebut: new \DateTimeImmutable($dateDebut),
            dateFin: new \DateTimeImmutable($dateFin)
        );
    }
}