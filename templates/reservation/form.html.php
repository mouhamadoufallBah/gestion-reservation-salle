<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle réservation</title>
</head>

<body>

<h1>Nouvelle réservation</h1>

<form method="POST" action="/reservations">

    <div>
        <label for="salle">
            Salle
        </label>

        <select id="salle" name="salle_id">

            <option value="">
                -- Choisir une salle --
            </option>

            <option value="1">
                Amphithéâtre A
            </option>

            <option value="2">
                Salle B12
            </option>

            <option value="3">
                Laboratoire Chimie
            </option>

        </select>
    </div>

    <div>
        <label for="date">
            Date
        </label>

        <input
            type="date"
            id="date"
            name="date"
        >
    </div>

    <div>
        <label for="heure_debut">
            Heure de début
        </label>

        <input
            type="time"
            id="heure_debut"
            name="heure_debut"
        >
    </div>

    <div>
        <label for="heure_fin">
            Heure de fin
        </label>

        <input
            type="time"
            id="heure_fin"
            name="heure_fin"
        >
    </div>

    <button type="submit">
        Créer la réservation
    </button>

</form>

<a href="/reservations">
    Retour
</a>

</body>
</html>