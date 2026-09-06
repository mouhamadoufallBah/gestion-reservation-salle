<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gestion des salles</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #222;
        }

        header {
            background: #222;
            padding: 20px;
        }

        nav {
            max-width: 1100px;
            margin: auto;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 25px;
        }

        main {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            background: #222;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .btn-danger {
            background: #c62828;
        }

        input,
        select {
            width: 100%;
            max-width: 500px;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

<header>
    <nav>
        <a href="/salles">Salles</a>
        <a href="/reservations">Réservations</a>
    </nav>
</header>

<main>

   <?= $contenu ?? $contenu = '' ?>

</main>

</body>
</html>