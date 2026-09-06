<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Salles</title>
</head>

<body>

<h1>Liste des salles</h1>

<a href="/salles/create">
    Ajouter une salle
</a>

<hr>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Capacité</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <tr>
            <td>1</td>
            <td>Amphithéâtre A</td>
            <td>250 places</td>
            <td>
                <a href="/salles/1">Voir</a>
                <a href="/salles/1/edit">Modifier</a>
            </td>
        </tr>

        <tr>
            <td>2</td>
            <td>Salle B12</td>
            <td>40 places</td>
            <td>
                <a href="/salles/2">Voir</a>
                <a href="/salles/2/edit">Modifier</a>
            </td>
        </tr>

        <tr>
            <td>3</td>
            <td>Laboratoire Chimie</td>
            <td>24 places</td>
            <td>
                <a href="/salles/3">Voir</a>
                <a href="/salles/3/edit">Modifier</a>
            </td>
        </tr>

    </tbody>

</table>

</body>
</html>