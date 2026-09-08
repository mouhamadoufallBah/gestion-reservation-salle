<?php $salles = $salles ?? []; ?>

<h1>Créer une réservation</h1>

<form method="POST" action="/reservations">

    <div>
        <label for="salleId">Salle</label>

        <select name="salleId" id="salleId">

            <option value="">
                Choisir une salle
            </option>

            <?php foreach ($salles as $salle): ?>

                <option
                    value="<?= $salle->id ?>"
                    <?= ($reservation['salleId'] ?? '') == $salle->id ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($salle->nom) ?>
                    - <?= $salle->capacite ?> places
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <div>
        <label for="responsable">Responsable</label>

        <input
            type="text"
            name="responsable"
            id="responsable"
            value="<?= htmlspecialchars($reservation['responsable'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="email">Email</label>

        <input
            type="email"
            name="email"
            id="email"
            value="<?= htmlspecialchars($reservation['email'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="motif">Motif</label>

        <textarea
            name="motif"
            id="motif"
        ><?= htmlspecialchars($reservation['motif'] ?? '') ?></textarea>
    </div>

    <div>
        <label for="dateDebut">Date début</label>

        <input
            type="datetime-local"
            name="dateDebut"
            id="dateDebut"
            value="<?= htmlspecialchars($reservation['dateDebut'] ?? '') ?>"
        >
    </div>

    <div>
        <label for="dateFin">Date fin</label>

        <input
            type="datetime-local"
            name="dateFin"
            id="dateFin"
            value="<?= htmlspecialchars($reservation['dateFin'] ?? '') ?>"
        >
    </div>

    <button type="submit">
        Réserver
    </button>

</form>