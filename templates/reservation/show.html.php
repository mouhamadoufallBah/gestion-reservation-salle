<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail réservation</title>
</head>

<body>

<h1>Détail de la réservation</h1>

<div>

    <h2>Réservation #1</h2>

    <p>
        <strong>Salle :</strong>
        Amphithéâtre A
    </p>

    <p>
        <strong>Date :</strong>
        10/09/2026
    </p>

    <p>
        <strong>Heure de début :</strong>
        08:00
    </p>

    <p>
        <strong>Heure de fin :</strong>
        10:00
    </p>

    <p>
        <strong>Statut :</strong>
        Confirmée
    </p>

    <form method="POST" action="/reservations/1/cancel">

        <button type="submit">
            Annuler la réservation
        </button>

    </form>

</div>

<a href="/reservations">
    Retour aux réservations
</a>

</body>
</html>