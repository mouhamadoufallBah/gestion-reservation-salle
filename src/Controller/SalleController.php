<?php

use App\DTO\CreerSalleDTO;
use App\DTO\ModifierSalleDTO;
use App\Model\TypeSalleEnum;
use App\Service\CreerSalleService;
use App\Validation\ValidatorInterface;

class SalleController
{
    public function __construct(
        private CreerSalleService $creerSalleService,
        private ValidatorInterface $validator
    ) {
    }

    public function index(): void
    {
        View::getInstance()->renderView('salle/index');
    }

    public function show(): void
    {
        View::getInstance()->renderView('salle/show');
    }

    public function create(): void
    {
        View::getInstance()->renderView('salle/form');
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

        if (!empty($errors)) {
            View::getInstance()->renderView('salle/form');
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

    public function edit(): void
    {
        View::getInstance()->renderView('salle/form');
    }

    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $nom = $_POST['nom'] ?? '';
        $batiment = $_POST['batiment'] ?? '';
        $capacite = (int) ($_POST['capacite'] ?? 0);
        $type = $_POST['type'] ?? '';
        $active = isset($_POST['active']);

        $dto = new ModifierSalleDTO(
            id: $id,
            nom: $nom,
            batiment: $batiment,
            capacite: $capacite,
            type: TypeSalleEnum::from($type),
            active: $active
        );

        var_dump($dto);
    }
}