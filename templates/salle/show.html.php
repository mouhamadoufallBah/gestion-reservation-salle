<?php $salle = $salle ?? null; ?>

<?php if ($salle): ?>
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header">
            <h2><?= htmlspecialchars($salle->nom) ?></h2>
            <span class="badge <?= $salle->active ? 'badge-success' : 'badge-danger' ?>">
                <?= $salle->active ? 'Active' : 'Inactive' ?>
            </span>
        </div>

        <ul class="detail-list">
            <li>
                <span class="detail-label">ID</span>
                <span>#<?= $salle->id ?></span>
            </li>
            <li>
                <span class="detail-label">Bâtiment</span>
                <span><?= htmlspecialchars($salle->batiment) ?></span>
            </li>
            <li>
                <span class="detail-label">Capacité</span>
                <span><?= $salle->capacite ?> places</span>
            </li>
            <li>
                <span class="detail-label">Type</span>
                <span><?= htmlspecialchars(is_object($salle->type) ? $salle->type->value : (string) $salle->type) ?></span>
            </li>
            <li>
                <span class="detail-label">Statut</span>
                <span><?= $salle->active ? 'Active (disponible aux réservations)' : 'Inactive (réservations bloquées)' ?></span>
            </li>
        </ul>

        <div class="form-actions" style="margin-top: 30px;">
            <a href="/salles/<?= $salle->id ?>/edit" class="btn btn-primary">
                Modifier cette salle
            </a>
            <a href="/salles" class="btn btn-secondary">
                ← Retour aux salles
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card" style="max-width: 600px; margin: 40px auto; text-align: center;">
        <h2>Salle introuvable</h2>
        <p class="text-muted">La salle demandée n'existe pas ou a été supprimée.</p>
        <div style="margin-top: 20px;">
            <a href="/salles" class="btn btn-primary">Retour à la liste des salles</a>
        </div>
    </div>
<?php endif; ?>