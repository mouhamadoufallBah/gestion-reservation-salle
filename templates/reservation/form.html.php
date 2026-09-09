<?php
$salles = $salles ?? [];
$errors = $errors ?? [];
$reservation = $reservation ?? [];

$salleId = is_object($reservation) ? $reservation->salleId : ($reservation['salleId'] ?? '');
$responsable = is_object($reservation) ? $reservation->responsable : ($reservation['responsable'] ?? '');
$email = is_object($reservation) ? $reservation->email : ($reservation['email'] ?? '');
$motif = is_object($reservation) ? $reservation->motif : ($reservation['motif'] ?? '');

$dateDebut = '';
if (is_object($reservation) && isset($reservation->dateDebut)) {
    $dateDebut = $reservation->dateDebut instanceof \DateTimeInterface ? $reservation->dateDebut->format('Y-m-d\TH:i') : (string) $reservation->dateDebut;
} elseif (is_array($reservation) && isset($reservation['dateDebut'])) {
    $dateDebut = $reservation['dateDebut'];
}

$dateFin = '';
if (is_object($reservation) && isset($reservation->dateFin)) {
    $dateFin = $reservation->dateFin instanceof \DateTimeInterface ? $reservation->dateFin->format('Y-m-d\TH:i') : (string) $reservation->dateFin;
} elseif (is_array($reservation) && isset($reservation['dateFin'])) {
    $dateFin = $reservation['dateFin'];
}
?>

<div class="form-card">
    <h1>Créer une réservation</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <strong>Veuillez corriger les erreurs ci-dessous :</strong>
            <ul>
                <?php foreach ($errors as $field => $errorMsg): ?>
                    <li><?= htmlspecialchars($errorMsg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/reservations">

        <div class="form-group">
            <label for="salleId">Salle</label>
            <select
                name="salleId"
                id="salleId"
                class="form-control <?= !empty($errors['salleId']) ? 'is-invalid' : '' ?>"
            >
                <option value="">-- Choisir une salle --</option>
                <?php foreach ($salles as $salle): ?>
                    <option
                        value="<?= $salle->id ?>"
                        <?= (string) $salleId === (string) $salle->id ? 'selected' : '' ?>
                        <?= !$salle->active ? 'disabled style="color: #9ca3af;"' : '' ?>
                    >
                        <?= htmlspecialchars($salle->nom) ?> (<?= $salle->capacite ?> places)
                        <?= !$salle->active ? ' - [Inactive]' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['salleId'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['salleId']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="responsable">Nom du responsable</label>
            <input
                type="text"
                name="responsable"
                id="responsable"
                class="form-control <?= !empty($errors['responsable']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $responsable) ?>"
                placeholder="Ex : Dupont Jean"
            >
            <?php if (!empty($errors['responsable'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['responsable']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input
                type="email"
                name="email"
                id="email"
                class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $email) ?>"
                placeholder="Ex : jean.dupont@exemple.fr"
            >
            <?php if (!empty($errors['email'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="motif">Motif de la réservation</label>
            <textarea
                name="motif"
                id="motif"
                class="form-control <?= !empty($errors['motif']) ? 'is-invalid' : '' ?>"
                placeholder="Ex : Réunion d'équipe projet..."
            ><?= htmlspecialchars((string) $motif) ?></textarea>
            <?php if (!empty($errors['motif'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['motif']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="dateDebut">Date et heure de début</label>
            <input
                type="datetime-local"
                name="dateDebut"
                id="dateDebut"
                class="form-control <?= !empty($errors['dateDebut']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $dateDebut) ?>"
            >
            <?php if (!empty($errors['dateDebut'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['dateDebut']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="dateFin">Date et heure de fin</label>
            <input
                type="datetime-local"
                name="dateFin"
                id="dateFin"
                class="form-control <?= !empty($errors['dateFin']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $dateFin) ?>"
            >
            <?php if (!empty($errors['dateFin'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['dateFin']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                Confirmer la réservation
            </button>
            <a href="/reservations" class="btn btn-secondary">
                Annuler
            </a>
        </div>

    </form>
</div>