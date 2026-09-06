<?php

use App\Repository\Database;
use App\Validation\SalleValidator;

define('BASE_PATH', dirname(__DIR__));

require_once(BASE_PATH . "/vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

try {
    Database::getInstance();

    echo "Connexion réussie !";

    // $salleValidator = new SalleValidator();

    // $data = [
    //     'nom' => 'Aw',
    //     'batiment' => 'Aa',
    //     'capacite' => 500,
    //     'type' => 'reunio',
    //     'active' => '1',
    // ];

    // $resultat = $salleValidator->validate($data);

    // if (!$resultat->isValid()) {

    //     $errors = $resultat->errors();

    //     print_r($errors);
    // } else {

    //     $dataValide = $resultat->data();

    //     print_r($dataValide);
    //     // Enregistrement...
    // }
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
