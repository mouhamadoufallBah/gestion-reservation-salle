<?php

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Service\AfficherReservationService;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Validation\AnnulationReservationValidator;
use App\DTO\PaginationDTO;
use App\Validation\ReservationValidator;
use DateTimeImmutable;
use Throwable;

class ReservationJsonController implements IReservationController
{
    public function __construct(
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService,
        private ReservationValidator $validator,
        private ListerSallesService $listerSallesService,
        private ListerReservationsService $listerReservationsService,
        private AfficherReservationService $afficherReservationService,
        private AnnulationReservationValidator $annulationValidator
    ) {}

    private function jsonResponse(mixed $data, int $status = 200): void
    {
        header_remove();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public function index(): void
    {
        $criteres = [
            'salle_id' => trim($_GET['salle_id'] ?? ''),
            'responsable' => trim($_GET['responsable'] ?? ''),
            'motif' => trim($_GET['motif'] ?? ''),
            'dateDebut' => trim($_GET['dateDebut'] ?? ''),
            'dateFin' => trim($_GET['dateFin'] ?? ''),
            'statut' => trim($_GET['statut'] ?? ''),
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $parPage = max(1, min(50, (int) ($_GET['limit'] ?? 6)));

        $salles = $this->listerSallesService->execute();
        $total = $this->listerReservationsService->compter($criteres);
        $reservations = $this->listerReservationsService->execute($criteres, $page, $parPage);

        $pagination = new PaginationDTO(
            page: $page,
            parPage: $parPage,
            total: $total,
            queryParams: array_filter($criteres, fn($v) => $v !== '' && $v !== null)
        );

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'reservations' => $reservations,
                'salles' => $salles,
                'pagination' => $pagination,
                'criteres' => $criteres,
            ]
        ]);
    }

    public function show(string $id): void
    {
        try {
            $reservation = $this->afficherReservationService->execute((int) $id);

            $this->jsonResponse([
                'status' => 'success',
                'data' => [
                    'reservation' => $reservation
                ]
            ]);
        } catch (ReservationIntrouvableException $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function create(): void
    {
        $salles = $this->listerSallesService->execute();

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Endpoint de création de réservation. Veuillez envoyer une requête POST.',
            'data' => [
                'salles' => $salles
            ]
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

        $validation = $this->validator->validate($data);

        if (!empty($validation->errors())) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur de validation',
                'errors' => $validation->errors()
            ], 422);
        }

        try {
            $dateDebut = new DateTimeImmutable($data['dateDebut']);
            $dateFin = new DateTimeImmutable($data['dateFin']);
        } catch (Throwable) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Format de date invalide.',
                'errors' => ['dateDebut' => 'Format de date invalide.']
            ], 422);
        }

        $dto = new CreerReservationDTO(
            salleId: (int) $data['salleId'],
            responsable: trim($data['responsable']),
            email: trim($data['email']),
            motif: trim($data['motif']),
            dateDebut: $dateDebut,
            dateFin: $dateFin
        );

        try {
            $reservationCreee = $this->creerReservationService->execute($dto);

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'La réservation a été créée avec succès.',
                'data' => $reservationCreee ?? $data
            ], 201);
        } catch (SalleIndisponibleException $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => ['general' => $e->getMessage()]
            ], 422);
        } catch (Throwable $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur inattendue : ' . $e->getMessage(),
                'errors' => ['general' => 'Erreur inattendue : ' . $e->getMessage()]
            ], 500);
        }
    }

    public function cancel(string $id): void
    {
        $validation = $this->annulationValidator->validate(['id' => $id]);

        if (!empty($validation->errors())) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Identifiant de réservation invalide.',
                'errors' => $validation->errors()
            ], 422);
        }

        try {
            $this->annulerReservationService->execute((int) $id);

            $this->jsonResponse([
                'status' => 'success',
                'message' => 'La réservation a été annulée avec succès.'
            ]);
        } catch (ReservationIntrouvableException $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        } catch (Throwable $e) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => "Erreur lors de l'annulation : " . $e->getMessage()
            ], 500);
        }
    }
}
