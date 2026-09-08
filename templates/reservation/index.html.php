<?php

$reservations = $reservations ?? [];

?>

<h1>Liste des réservations</h1>

<a href="/reservations/create">
    Nouvelle réservation
</a>

<hr>

<table>

    <thead>
        <tr>
            <th>ID</th>
            <th>Salle ID</th>
            <th>Responsable</th>
            <th>Email</th>
            <th>Motif</th>
            <th>Date début</th>
            <th>Date fin</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($reservations as $reservation): ?>

            <tr>

                <td>
                    <?= $reservation->id ?>
                </td>

                <td>
                    <?= $reservation->salleId ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->responsable) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->email) ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->motif) ?>
                </td>

                <td>
                    <?= $reservation->dateDebut->format('d/m/Y H:i') ?>
                </td>

                <td>
                    <?= $reservation->dateFin->format('d/m/Y H:i') ?>
                </td>

                <td>
                    <?= htmlspecialchars($reservation->statut->value) ?>
                </td>

                <td>

                    <a href="/reservations/<?= $reservation->id ?>">
                        Voir
                    </a>

                    <?php if ($reservation->statut->value !== 'annulée'): ?>

                        <form
                            action="/reservations/<?= $reservation->id ?>/cancel"
                            method="POST">
                            <input type="hidden" name="id" value="<?= $reservation->id ?>">

                            <button type="submit">
                                Annuler
                            </button>
                        </form>

                    <?php endif; ?>

                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>