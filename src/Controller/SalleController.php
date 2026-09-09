<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Exception\SalleIntrouvableException;
use App\Model\TypeSalleEnum;
use App\Service\AfficherSalleService;
use App\Service\CreerSalleService;
use App\Service\FlashService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Validation\SalleValidator;
use App\DTO\PaginationDTO;
use App\View\View;
use Throwable;

class SalleController
{
    public function __construct(
        private CreerSalleService $creerSalleService,
        private ModifierSalleService $modifierSalleService,
        private ListerSallesService $listerSallesService,
        private AfficherSalleService $afficherSalleService,
        private SalleValidator $validator,
        private FlashService $flashService
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
        $salles = $this->listerSallesService->execute($criteres, $page, $parPage);

        $pagination = new PaginationDTO(
            page: $page,
            parPage: $parPage,
            total: $total,
            queryParams: array_filter($criteres, fn($v) => $v !== '' && $v !== null)
        );

        View::getInstance()->renderView('salle/index', [
            'salles' => $salles,
            'pagination' => $pagination,
            'criteres' => $criteres,
        ]);
    }

    public function show(string $id): void
    {
        try {
            $salle = $this->afficherSalleService->execute((int) $id);

            View::getInstance()->renderView('salle/show', [
                'salle' => $salle
            ]);
        } catch (SalleIntrouvableException $e) {
            http_response_code(404);
            View::getInstance()->renderView('errors/404', [
                'message' => $e->getMessage()
            ]);
        }
    }

    public function create(): void
    {
        View::getInstance()->renderView('salle/form', [
            'salle' => null
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
            View::getInstance()->renderView('salle/form', [
                'errors' => $errors->errors(),
                'salle' => $data
            ]);
            return;
        }

        try {
            $dto = new CreerSalleDTO(
                nom: trim($data['nom']),
                batiment: trim($data['batiment']),
                capacite: (int) $data['capacite'],
                type: TypeSalleEnum::from($data['type']),
                active: $data['active']
            );

            $this->creerSalleService->execute($dto);

            $this->flashService->success('La salle a été créée avec succès.');

            header('Location: /salles');
            exit;
        } catch (Throwable $e) {
            View::getInstance()->renderView('salle/form', [
                'errors' => ['general' => 'Erreur lors de la création de la salle : ' . $e->getMessage()],
                'salle' => $data
            ]);
        }
    }

    public function edit(string $id): void
    {
        try {
            $salle = $this->afficherSalleService->execute((int) $id);

            View::getInstance()->renderView('salle/form', [
                'salle' => $salle
            ]);
        } catch (SalleIntrouvableException $e) {
            http_response_code(404);
            View::getInstance()->renderView('errors/404', [
                'message' => $e->getMessage()
            ]);
        }
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
            View::getInstance()->renderView('salle/form', [
                'errors' => $errors->errors(),
                'salle' => array_merge(
                    ['id' => $id],
                    $data
                )
            ]);
            return;
        }

        try {
            $dto = new ModifierSalleDTO(
                id: (int) $id,
                nom: trim($data['nom']),
                batiment: trim($data['batiment']),
                capacite: (int) $data['capacite'],
                type: TypeSalleEnum::from($data['type']),
                active: $data['active']
            );

            $this->modifierSalleService->execute($dto);

            $this->flashService->success('La salle a été modifiée avec succès.');

            header('Location: /salles/' . $id);
            exit;
        } catch (SalleIntrouvableException $e) {
            http_response_code(404);
            View::getInstance()->renderView('errors/404', [
                'message' => $e->getMessage()
            ]);
        } catch (Throwable $e) {
            View::getInstance()->renderView('salle/form', [
                'errors' => ['general' => 'Erreur lors de la modification : ' . $e->getMessage()],
                'salle' => array_merge(
                    ['id' => $id],
                    $data
                )
            ]);
        }
    }
}
