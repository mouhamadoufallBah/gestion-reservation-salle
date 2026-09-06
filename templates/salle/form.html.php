<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une salle</title>
</head>

<body>

<h1>Créer une salle</h1>

<form method="POST" action="/salles">

    <div>
        <label for="nom">
            Nom de la salle
        </label>

        <input
            type="text"
            id="nom"
            name="nom"
            placeholder="Ex : Salle B12"
        >
    </div>

    <div>
        <label for="capacite">
            Capacité
        </label>

        <input
            type="number"
            id="capacite"
            name="capacite"
            placeholder="Ex : 40"
        >
    </div>

    <button type="submit">
        Créer la salle
    </button>

</form>

<a href="/salles">
    Retour
</a>

</body>
</html>