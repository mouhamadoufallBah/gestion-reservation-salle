<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\TypeSalleEnum;
use App\Service\AfficherSalleService;
use App\Service\CreerSalleService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Validation\SalleValidator;
use App\View\View;

class SalleController
{
    public function __construct(
        private CreerSalleService $creerSalleService,
        private ModifierSalleService $modifierSalleService,
        private ListerSallesService $listerSallesService,
        private AfficherSalleService $afficherSalleService,
        private SalleValidator $validator
    ) {}

    public function index(): void
    {
        $salles = $this->listerSallesService->execute();

        View::getInstance()->renderView('salle/index', [
            'salles' => $salles
        ]);
    }

    public function show(string $id): void
    {
        
        $salle = $this->afficherSalleService->execute($id);

        View::getInstance()->renderView('salle/show', [
            'salle' => $salle
        ]);
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

        $dto = new CreerSalleDTO(
            nom: $data['nom'],
            batiment: $data['batiment'],
            capacite: (int) $data['capacite'],
            type: TypeSalleEnum::from($data['type']),
            active: $data['active']
        );

        $this->creerSalleService->execute($dto);

        header('Location: /salles');
        exit;
    }

    public function edit(string $id): void
    {
        $salle = $this->afficherSalleService->execute($id);

        View::getInstance()->renderView('salle/form', [
            'salle' => $salle
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
            View::getInstance()->renderView('salle/form', [
                'errors' => $errors->errors(),
                'salle' => array_merge(
                    ['id' => $id],
                    $data
                )
            ]);
            return;
        }

        $dto = new ModifierSalleDTO(
            id: $id,
            nom: $data['nom'],
            batiment: $data['batiment'],
            capacite: (int) $data['capacite'],
            type: TypeSalleEnum::from($data['type']),
            active: $data['active']
        );

        $this->modifierSalleService->execute($dto);

        header('Location: /salles/' . $id);
        exit;
    }
}
