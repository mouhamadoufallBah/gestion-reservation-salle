<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\TypeSalleEnum;
use App\Service\AfficherSalleService;
use App\Service\CreerSalleService;
use App\Service\FlashService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Validation\SalleValidator;
use App\DTO\PaginationDTO;
use App\View\View;

class SalleController implements ISalleController
{
    public function __construct(
        private CreerSalleService $creerSalleService,
        private ModifierSalleService $modifierSalleService,
        private ListerSallesService $listerSallesService,
        private AfficherSalleService $afficherSalleService,
        private SalleValidator $validator,
        private FlashService $flashService,
        private View $view
    ) {}

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

        $this->view->renderView('salle/index', [
            'salles' => $salles,
            'pagination' => $pagination,
            'criteres' => $criteres,
        ]);
    }

    public function show(string $id): void
    {
        $salle = $this->afficherSalleService->execute((int) $id);

        $this->view->renderView('salle/show', [
            'salle' => $salle,
        ]);
    }

    public function create(): void
    {
        $this->view->renderView('salle/form', [
            'salle' => null,
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
            $this->view->renderView('salle/form', [
                'errors' => $errors->errors(),
                'salle' => $data,
            ]);

            return;
        }

        $dto = new CreerSalleDTO(
            nom: trim($data['nom']),
            batiment: trim($data['batiment']),
            capacite: (int) $data['capacite'],
            type: TypeSalleEnum::from($data['type']),
            active: $data['active']
        );

        $this->creerSalleService->execute($dto);

        $this->flashService->success(
            'La salle a été créée avec succès.'
        );

        header('Location: /salles');
        exit;
    }

    public function edit(string $id): void
    {
        $salle = $this->afficherSalleService->execute((int) $id);

        $this->view->renderView('salle/form', [
            'salle' => $salle,
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
            $this->view->renderView('salle/form', [
                'errors' => $errors->errors(),
                'salle' => array_merge(
                    ['id' => $id],
                    $data
                ),
            ]);

            return;
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

        $this->flashService->success(
            'La salle a été modifiée avec succès.'
        );

        header('Location: /salles/' . $id);
        exit;
    }
}