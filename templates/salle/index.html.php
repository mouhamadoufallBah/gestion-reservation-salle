<?php
$criteres = $criteres ?? [];
$pagination = $pagination ?? null;
?>

<div class="actions">
    <h1>Liste des salles</h1>
    <a href="/salles/create" class="btn btn-primary">
        + Ajouter une salle
    </a>
</div>

<div class="search-card">
    <div class="search-title">
        <span>🔍 Recherche multicritère</span>
    </div>
    <form method="GET" action="/salles">
        <div class="search-grid">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="q">Mot-clé (Nom ou Bâtiment)</label>
                <input
                    type="text"
                    id="q"
                    name="q"
                    class="form-control"
                    placeholder="Ex : Amphithéâtre..."
                    value="<?= htmlspecialchars($criteres['q'] ?? '') ?>"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="type">Type de salle</label>
                <select id="type" name="type" class="form-control">
                    <option value="">-- Tous les types --</option>
                    <?php foreach (\App\Model\TypeSalleEnum::cases() as $type): ?>
                        <option
                            value="<?= $type->value ?>"
                            <?= ($criteres['type'] ?? '') === $type->value ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($type->value) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="capacite_min">Capacité minimale</label>
                <input
                    type="number"
                    id="capacite_min"
                    name="capacite_min"
                    min="1"
                    class="form-control"
                    placeholder="Ex : 20"
                    value="<?= htmlspecialchars($criteres['capacite_min'] ?? '') ?>"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" class="form-control">
                    <option value="">-- Tous les statuts --</option>
                    <option value="active" <?= ($criteres['statut'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= ($criteres['statut'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="search-actions">
            <button type="submit" class="btn btn-primary">
                Filtrer
            </button>
            <a href="/salles" class="btn btn-secondary">
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
                <th>Nom</th>
                <th>Bâtiment</th>
                <th>Capacité</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($salles)): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <p>Aucune salle ne correspond à vos critères de recherche.</p>
                            <a href="/salles" class="btn btn-secondary">Réinitialiser les filtres</a>
                        </div>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($salles as $salle): ?>
                    <tr>
                        <td><?= $salle->id ?></td>
                        <td><strong><?= htmlspecialchars($salle->nom) ?></strong></td>
                        <td><?= htmlspecialchars($salle->batiment) ?></td>
                        <td><?= $salle->capacite ?> places</td>
                        <td><?= htmlspecialchars(is_object($salle->type) ? $salle->type->value : (string) $salle->type) ?></td>
                        <td>
                            <span class="badge <?= $salle->active ? 'badge-success' : 'badge-danger' ?>">
                                <?= $salle->active ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="/salles/<?= $salle->id ?>" class="btn btn-sm btn-secondary">Voir</a>
                                <a href="/salles/<?= $salle->id ?>/edit" class="btn btn-sm btn-primary">Modifier</a>
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
            Affichage de <?= $pagination->debut() ?> à <?= $pagination->fin() ?> sur <?= $pagination->total ?> salle(s)
        </div>
    </div>
<?php endif; ?>