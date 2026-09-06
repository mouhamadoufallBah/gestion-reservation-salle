<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations</title>
</head>

<body>

<h1>Liste des réservations</h1>

<a href="/reservations/create">
    Nouvelle réservation
</a>

<hr>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Salle</th>
            <th>Date</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <tr>
            <td>1</td>
            <td>Amphithéâtre A</td>
            <td>10/09/2026</td>
            <td>08:00</td>
            <td>10:00</td>
            <td>Confirmée</td>
            <td>
                <a href="/reservations/1">
                    Voir
                </a>
            </td>
        </tr>

        <tr>
            <td>2</td>
            <td>Salle B12</td>
            <td>11/09/2026</td>
            <td>14:00</td>
            <td>16:00</td>
            <td>Confirmée</td>
            <td>
                <a href="/reservations/2">
                    Voir
                </a>
            </td>
        </tr>

    </tbody>

</table>

</body>
</html>