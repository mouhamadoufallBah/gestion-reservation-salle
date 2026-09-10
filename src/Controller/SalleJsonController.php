<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\DTO\PaginationDTO;
use App\Model\TypeSalleEnum;
use App\Service\AfficherSalleService;
use App\Service\CreerSalleService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Validation\SalleValidator;

class SalleJsonController implements ISalleController
{
    public function __construct(
        private CreerSalleService $creerSalleService,
        private ModifierSalleService $modifierSalleService,
        private ListerSallesService $listerSallesService,
        private AfficherSalleService $afficherSalleService,
        private SalleValidator $validator,
    ) {}

    private function jsonResponse(
        mixed $data,
        int $status = 200
    ): void {
        header_remove();
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );

        exit;
    }

    public function index(): void
    {
        $criteres = [
            'q' => trim($_GET['q'] ?? ''),
            'type' => trim($_GET['type'] ?? ''),
            'capacite_min' => trim($_GET['capacite_min'] ?? ''),
            'statut' => trim($_GET['statut'] ?? ''),
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $parPage = max(1, min(50, (int) ($_GET['limit'] ?? 6)));

        $total = $this->listerSallesService->compter($criteres);

        $salles = $this->listerSallesService->execute(
            $criteres,
            $page,
            $parPage
        );

        $pagination = new PaginationDTO(
            page: $page,
            parPage: $parPage,
            total: $total,
            queryParams: array_filter(
                $criteres,
                fn ($v) => $v !== '' && $v !== null
            )
        );

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'salles' => $salles,
                'pagination' => $pagination,
                'criteres' => $criteres,
            ],
        ]);
    }

    public function show(string $id): void
    {
        $salle = $this->afficherSalleService->execute((int) $id);

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'salle' => $salle,
            ],
        ]);
    }

    public function create(): void
    {
        $this->jsonResponse([
            'status' => 'success',
            'message' => 'Endpoint de création. Veuillez envoyer une requête POST avec les champs requis.',
        ]);
    }

    public function store(): void
    {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => $_POST['capacite'] ?? '',
            'type' => $_POST['type'] ?? '',
            'active' => isset($_POST['active']),
        ];

        $errors = $this->validator->validate($data);

        if (!empty($errors->errors())) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur de validation.',
                'errors' => $errors->errors(),
            ], 422);
        }

        $dto = new CreerSalleDTO(
            nom: trim($data['nom']),
            batiment: trim($data['batiment']),
            capacite: (int) $data['capacite'],
            type: TypeSalleEnum::from($data['type']),
            active: $data['active']
        );

        $salleCreee = $this->creerSalleService->execute($dto);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'La salle a été créée avec succès.',
            'data' => $salleCreee,
        ], 201);
    }

    public function edit(string $id): void
    {
        $salle = $this->afficherSalleService->execute((int) $id);

        $this->jsonResponse([
            'status' => 'success',
            'data' => [
                'salle' => $salle,
            ],
        ]);
    }

    public function update(string $id): void
    {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'batiment' => $_POST['batiment'] ?? '',
            'capacite' => $_POST['capacite'] ?? '',
            'type' => $_POST['type'] ?? '',
            'active' => isset($_POST['active']),
        ];

        $errors = $this->validator->validate($data);

        if (!empty($errors->errors())) {
            $this->jsonResponse([
                'status' => 'error',
                'message' => 'Erreur de validation.',
                'errors' => $errors->errors(),
            ], 422);
        }

        $dto = new ModifierSalleDTO(
            id: (int) $id,
            nom: trim($data['nom']),
            batiment: trim($data['batiment']),
            capacite: (int) $data['capacite'],
            type: TypeSalleEnum::from($data['type']),
            active: $data['active']
        );

        $this->modifierSalleService->execute($dto);

        $this->jsonResponse([
            'status' => 'success',
            'message' => 'La salle a été modifiée avec succès.',
        ]);
    }
}