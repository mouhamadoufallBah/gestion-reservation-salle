<?php

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Service\AfficherReservationService;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Validation\AnnulationReservationValidator;
use App\Validation\ReservationValidator;
use App\View\View;

class ReservationController
{
    public function __construct(
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService,
        private ReservationValidator $validator,
        private ListerSallesService $listerSallesService,
        private ListerReservationsService $listerReservationsService,
        private AfficherReservationService $afficherReservationService,
        private AnnulationReservationValidator $annulationValidator,
    ) {}

    public function index(): void
    {
        $reservations = $this->listerReservationsService->execute();

        View::getInstance()->renderView('reservation/index', [
            'reservations' => $reservations
        ]);
    }

    public function show(string $id): void
    {
        $reservation = $this->afficherReservationService->execute($id);

        View::getInstance()->renderView('reservation/show', [
            'reservation' => $reservation
        ]);
    }

    public function create(): void
    {
        $salles = $this->listerSallesService->execute();

        View::getInstance()->renderView('reservation/form', [
            'salles' => $salles
        ]);
    }

    public function store(): void
    {
        $data = [
            'salleId' => $_POST['salleId'] ?? '',
            'responsable' => $_POST['responsable'] ?? '',
            'email' => $_POST['email'] ?? '',
            'motif' => $_POST['motif'] ?? '',
            'dateDebut' => $_POST['dateDebut'] ?? '',
            'dateFin' => $_POST['dateFin'] ?? '',
        ];

        $dto = new CreerReservationDTO(
            salleId: (int) $data['salleId'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            dateDebut: new \DateTimeImmutable($data['dateDebut']),
            dateFin: new \DateTimeImmutable($data['dateFin'])
        );

        $errors = $this->validator->validate($data);


        if (!empty($errors->errors())) {
            View::getInstance()->renderView('reservation/form');
            return;
        }

        $this->creerReservationService->execute($dto);
        header('Location: /reservations');
        exit;
    }


    public function cancel(string $id): void
    {
        $data = [
            'id' => $id
        ];

        $errors = $this->annulationValidator->validate($data);

        if (!empty($errors->errors())) {
            var_dump($errors->errors());
            die;
        }

        $this->annulerReservationService->execute($id);

        header('Location: /reservations');
        exit;
    }
}
