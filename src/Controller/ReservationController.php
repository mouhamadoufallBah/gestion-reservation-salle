<?php
namespace App\Controller;

use App\DTO\AnnulerReservationDTO;
use App\DTO\CreerReservationDTO;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\ValidatorInterface;
use App\Validator\CreerReservationValidator;
use View;

class ReservationController
{
    public function __construct(
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService,
        private ValidatorInterface $validator
    ) {}

    public function index(): void
    {
        View::getInstance()->renderView('reservation/index');
    }

    public function show(): void
    {
        View::getInstance()->renderView('reservation/show');
    }

    public function create(): void
    {
        View::getInstance()->renderView('reservation/form');
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

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            View::getInstance()->renderView('reservation/form');
            return;
        }

        $dto = new CreerReservationDTO(
            salleId: (int) $data['salleId'],
            responsable: $data['responsable'],
            email: $data['email'],
            motif: $data['motif'],
            dateDebut: new \DateTimeImmutable($data['dateDebut']),
            dateFin: new \DateTimeImmutable($data['dateFin'])
        );

        $this->creerReservationService->execute($dto);

        header('Location: /reservations');
        exit;
    }

    public function cancel(): void
    {
        $data = [
            'id' => $_POST['id'] ?? '',
        ];

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            View::getInstance()->renderView('reservation/show');
            return;
        }

        $id = (int) $data['id'];

        $this->annulerReservationService->execute($id);

        header('Location: /reservations');
        exit;
    }
}
