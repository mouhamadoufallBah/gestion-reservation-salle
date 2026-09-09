<?php
$reservations = $reservations ?? [];
$salles = $salles ?? [];
$criteres = $criteres ?? [];
$pagination = $pagination ?? null;
?>

<div class="actions">
    <h1>Liste des réservations</h1>
    <a href="/reservations/create" class="btn btn-primary">
        + Nouvelle réservation
    </a>
</div>

<div class="search-card">
    <div class="search-title">
        <span>🔍 Recherche multicritère</span>
    </div>
    <form method="GET" action="/reservations">
        <div class="search-grid">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="salle_id">Salle</label>
                <select id="salle_id" name="salle_id" class="form-control">
                    <option value="">-- Toutes les salles --</option>
                    <?php foreach ($salles as $s): ?>
                        <option
                            value="<?= $s->id ?>"
                            <?= ($criteres['salle_id'] ?? '') == $s->id ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($s->nom) ?> (<?= $s->capacite ?> pl.)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="responsable">Responsable ou Email</label>
                <input
                    type="text"
                    id="responsable"
                    name="responsable"
                    class="form-control"
                    placeholder="Nom ou email..."
                    value="<?= htmlspecialchars($criteres['responsable'] ?? '') ?>"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="motif">Motif</label>
                <input
                    type="text"
                    id="motif"
                    name="motif"
                    class="form-control"
                    placeholder="Ex : Réunion..."
                    value="<?= htmlspecialchars($criteres['motif'] ?? '') ?>"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" class="form-control">
                    <option value="">-- Tous les statuts --</option>
                    <option value="confirmée" <?= ($criteres['statut'] ?? '') === 'confirmée' ? 'selected' : '' ?>>Confirmée</option>
                    <option value="annulée" <?= ($criteres['statut'] ?? '') === 'annulée' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="date_debut">Réservé à partir de</label>
                <input
                    type="datetime-local"
                    id="date_debut"
                    name="date_debut"
                    class="form-control"
                    value="<?= htmlspecialchars($criteres['date_debut'] ?? '') ?>"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="date_fin">Jusqu'au</label>
                <input
                    type="datetime-local"
                    id="date_fin"
                    name="date_fin"
                    class="form-control"
                    value="<?= htmlspecialchars($criteres['date_fin'] ?? '') ?>"
                >
            </div>
        </div>

        <div class="search-actions">
            <button type="submit" class="btn btn-primary">
                Filtrer
            </button>
            <a href="/reservations" class="btn btn-secondary">
                Réinitialiser
            </a>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Salle</th>
                <th>Responsable</th>
                <th>Email</th>
                <th>Motif</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)): ?>
                <tr>
                    <td colspan="9">
                        <div class="empty">
                            <p>Aucune réservation ne correspond à vos critères de recherche.</p>
                            <a href="/reservations" class="btn btn-secondary">Réinitialiser les filtres</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                    $isAnnulee = (is_object($reservation->statut) ? $reservation->statut->value : (string) $reservation->statut) === 'annulée';
                    ?>
                    <tr>
                        <td>#<?= $reservation->id ?></td>
                        <td>Salle #<?= $reservation->salleId ?></td>
                        <td><strong><?= htmlspecialchars($reservation->responsable) ?></strong></td>
                        <td><?= htmlspecialchars($reservation->email) ?></td>
                        <td><?= htmlspecialchars($reservation->motif) ?></td>
                        <td><?= $reservation->dateDebut instanceof \DateTimeInterface ? $reservation->dateDebut->format('d/m/Y H:i') : htmlspecialchars((string) $reservation->dateDebut) ?></td>
                        <td><?= $reservation->dateFin instanceof \DateTimeInterface ? $reservation->dateFin->format('d/m/Y H:i') : htmlspecialchars((string) $reservation->dateFin) ?></td>
                        <td>
                            <span class="badge <?= $isAnnulee ? 'badge-danger' : 'badge-success' ?>">
                                <?= htmlspecialchars(is_object($reservation->statut) ? $reservation->statut->value : (string) $reservation->statut) ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="/reservations/<?= $reservation->id ?>" class="btn btn-sm btn-secondary">
                                    Voir
                                </a>

                                <?php if (!$isAnnulee): ?>
                                    <form
                                        action="/reservations/<?= $reservation->id ?>/cancel"
                                        method="POST"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');"
                                    >
                                        <input type="hidden" name="id" value="<?= $reservation->id ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Annuler
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($pagination && $pagination->total > 0): ?>
    <div class="pagination-wrapper">
        <div class="pagination">
            <a
                href="<?= $pagination->hasPrevious() ? $pagination->url($pagination->previousPage()) : '#' ?>"
                class="pagination-item <?= !$pagination->hasPrevious() ? 'disabled' : '' ?>"
            >
                &laquo; Précédent
            </a>

            <?php for ($i = 1; $i <= $pagination->totalPages(); $i++): ?>
                <a
                    href="<?= $pagination->url($i) ?>"
                    class="pagination-item <?= $i === $pagination->page ? 'active' : '' ?>"
                >
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <a
                href="<?= $pagination->hasNext() ? $pagination->url($pagination->nextPage()) : '#' ?>"
                class="pagination-item <?= !$pagination->hasNext() ? 'disabled' : '' ?>"
            >
                Suivant &raquo;
            </a>
        </div>

        <div class="pagination-info">
            Affichage de <?= $pagination->debut() ?> à <?= $pagination->fin() ?> sur <?= $pagination->total ?> réservation(s)
        </div>
    </div>
<?php endif; ?>