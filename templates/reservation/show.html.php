<?php

use App\Model\StatutReservationEnum;

$reservation = $reservation ?? null;

?>

<h1>Détail de la réservation</h1>

<?php if ($reservation): ?>

    <div>

        <h2>
            Réservation #<?= $reservation->id ?>
        </h2>

        <p>
            <strong>Salle :</strong>
            <?= $reservation->salleId ?>
        </p>

        <p>
            <strong>Responsable :</strong>
            <?= htmlspecialchars($reservation->responsable) ?>
        </p>

        <p>
            <strong>Email :</strong>
            <?= htmlspecialchars($reservation->email) ?>
        </p>

        <p>
            <strong>Motif :</strong>
            <?= htmlspecialchars($reservation->motif) ?>
        </p>

        <p>
            <strong>Date :</strong>
            <?= $reservation->dateDebut->format('d/m/Y') ?>
        </p>

        <p>
            <strong>Heure de début :</strong>
            <?= $reservation->dateDebut->format('H:i') ?>
        </p>

        <p>
            <strong>Heure de fin :</strong>
            <?= $reservation->dateFin->format('H:i') ?>
        </p>

        <p>
            <strong>Statut :</strong>
            <?= htmlspecialchars($reservation->statut->value) ?>
        </p>

        <?php if ($reservation->statut === StatutReservationEnum::CONFIRMEE): ?>

            <form
                method="POST"
                action="/reservations/<?= $reservation->id ?>/cancel"
            >
                <button type="submit">
                    Annuler la réservation
                </button>
            </form>

        <?php endif; ?>

    </div>

<?php else: ?>

    <p>Réservation introuvable.</p>

<?php endif; ?>

<a href="/reservations">
    Retour aux réservations
</a>