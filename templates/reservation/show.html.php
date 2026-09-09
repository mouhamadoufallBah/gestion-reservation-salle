<?php
use App\Model\StatutReservationEnum;

$reservation = $reservation ?? null;
?>

<?php if ($reservation): ?>
    <?php
    $statutStr = is_object($reservation->statut) ? $reservation->statut->value : (string) $reservation->statut;
    $isConfirmee = $reservation->statut === StatutReservationEnum::CONFIRMEE || $statutStr === 'confirmée';
    ?>
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2>Réservation #<?= $reservation->id ?></h2>
            <span class="badge <?= $isConfirmee ? 'badge-success' : 'badge-danger' ?>">
                <?= htmlspecialchars($statutStr) ?>
            </span>
        </div>

        <ul class="detail-list">
            <li>
                <span class="detail-label">Salle</span>
                <span>
                    <strong>Salle #<?= $reservation->salleId ?></strong>
                    &nbsp;(<a href="/salles/<?= $reservation->salleId ?>">voir la salle</a>)
                </span>
            </li>
            <li>
                <span class="detail-label">Responsable</span>
                <span><?= htmlspecialchars($reservation->responsable) ?></span>
            </li>
            <li>
                <span class="detail-label">Email</span>
                <span><a href="mailto:<?= htmlspecialchars($reservation->email) ?>"><?= htmlspecialchars($reservation->email) ?></a></span>
            </li>
            <li>
                <span class="detail-label">Motif</span>
                <span><?= htmlspecialchars($reservation->motif) ?></span>
            </li>
            <li>
                <span class="detail-label">Date</span>
                <span><?= $reservation->dateDebut instanceof \DateTimeInterface ? $reservation->dateDebut->format('d/m/Y') : '' ?></span>
            </li>
            <li>
                <span class="detail-label">Créneau</span>
                <span>
                    <?= $reservation->dateDebut instanceof \DateTimeInterface ? $reservation->dateDebut->format('H:i') : '' ?>
                    &nbsp;à&nbsp;
                    <?= $reservation->dateFin instanceof \DateTimeInterface ? $reservation->dateFin->format('H:i') : '' ?>
                </span>
            </li>
            <li>
                <span class="detail-label">Statut</span>
                <span><?= htmlspecialchars($statutStr) ?></span>
            </li>
        </ul>

        <div class="form-actions" style="margin-top: 30px;">
            <?php if ($isConfirmee): ?>
                <form
                    method="POST"
                    action="/reservations/<?= $reservation->id ?>/cancel"
                    onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');"
                >
                    <button type="submit" class="btn btn-danger">
                        Annuler la réservation
                    </button>
                </form>
            <?php endif; ?>

            <a href="/reservations" class="btn btn-secondary">
                ← Retour aux réservations
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card" style="max-width: 600px; margin: 40px auto; text-align: center;">
        <h2>Réservation introuvable</h2>
        <p class="text-muted">La réservation demandée n'existe pas ou a été supprimée.</p>
        <div style="margin-top: 20px;">
            <a href="/reservations" class="btn btn-primary">Retour à la liste des réservations</a>
        </div>
    </div>
<?php endif; ?>