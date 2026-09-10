<?php

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\DTO\CreerReservationDTOBuilder;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Service\AfficherReservationService;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\FlashService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Validation\AnnulationReservationValidator;
use App\DTO\PaginationDTO;
use App\Validation\ReservationValidator;
use App\View\View;
use DateTimeImmutable;


class ReservationController implements IReservationController
{
    public function __construct(
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService,
        private ReservationValidator $validator,
        private ListerSallesService $listerSallesService,
        private ListerReservationsService $listerReservationsService,
        private AfficherReservationService $afficherReservationService,
        private AnnulationReservationValidator $annulationValidator,
        private FlashService $flashService,
        private View $view
    ) {}

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

        $this->view->renderView('reservation/index', [
            'reservations' => $reservations,
            'salles' => $salles,
            'pagination' => $pagination,
            'criteres' => $criteres,
        ]);
    }

    public function show(string $id): void
    {
        try {
            $reservation = $this->afficherReservationService->execute((int) $id);

            $this->view->renderView('reservation/show', [
                'reservation' => $reservation
            ]);
        } catch (ReservationIntrouvableException $e) {
            http_response_code(404);
            $this->view->renderView('errors/404', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(): void
    {
        $salles = $this->listerSallesService->execute();

        $this->view->renderView('reservation/form', [
            'salles' => $salles,
            'reservation' => null,
            'errors' => []
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

        if (!$validation->isValid()) {
            $salles = $this->listerSallesService->execute();

            $this->view->renderView('reservation/form', [
                'salles' => $salles,
                'errors' => $validation->errors(),
                'reservation' => $data,
            ]);

            return;
        }

        try {
            $builder = new CreerReservationDTOBuilder();

            $dto = $builder
                ->fromArray($validation->data())
                ->build();

            $this->creerReservationService->execute($dto);

            $this->flashService->success(
                'La réservation a été créée avec succès.'
            );

            header('Location: /reservations');
            exit;
        } catch (SalleIndisponibleException $e) {
            $salles = $this->listerSallesService->execute();

            $this->view->renderView('reservation/form', [
                'salles' => $salles,
                'errors' => [
                    'general' => $e->getMessage(),
                ],
                'reservation' => $data,
            ]);

            return;
        } catch (\Throwable $e) {
            $salles = $this->listerSallesService->execute();

            $this->view->renderView('reservation/form', [
                'salles' => $salles,
                'errors' => [
                    'general' => 'Erreur inattendue : ' . $e->getMessage(),
                ],
                'reservation' => $data,
            ]);

            return;
        }
    }

    public function cancel(string $id): void
    {
        $validation = $this->annulationValidator->validate(['id' => $id]);

        if (!empty($validation->errors())) {
            $this->flashService->error("Identifiant de réservation invalide.");
            header('Location: /reservations');
            exit;
        }

        try {
            $this->annulerReservationService->execute((int) $id);
            $this->flashService->success('La réservation a été annulée avec succès.');
        } catch (ReservationIntrouvableException $e) {
            $this->flashService->error($e->getMessage());
        } catch (\Throwable $e) {
            $this->flashService->error("Erreur lors de l'annulation : " . $e->getMessage());
        }

        header('Location: /reservations');
        exit;
    }
}
