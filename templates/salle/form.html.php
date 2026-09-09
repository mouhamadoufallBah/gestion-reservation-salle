<?php
$isEdit = false;
$id = null;
$nom = '';
$batiment = '';
$capacite = '';
$typeVal = '';
$active = true;

if (is_object($salle ?? null)) {
    $isEdit = true;
    $id = $salle->id;
    $nom = $salle->nom;
    $batiment = $salle->batiment;
    $capacite = $salle->capacite;
    $typeVal = is_object($salle->type) ? $salle->type->value : (string) $salle->type;
    $active = (bool) $salle->active;
} elseif (is_array($salle ?? null)) {
    $id = $salle['id'] ?? null;
    $isEdit = !empty($id);
    $nom = $salle['nom'] ?? '';
    $batiment = $salle['batiment'] ?? '';
    $capacite = $salle['capacite'] ?? '';
    $typeVal = $salle['type'] ?? '';
    $active = !empty($salle['active']);
}

$errors = $errors ?? [];
?>

<div class="form-card">
    <h1><?= $isEdit ? 'Modifier la salle' : 'Ajouter une salle' ?></h1>

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

    <form method="POST" action="<?= $isEdit ? '/salles/' . htmlspecialchars((string) $id) . '/edit' : '/salles' ?>">

        <div class="form-group">
            <label for="nom">Nom de la salle</label>
            <input
                type="text"
                id="nom"
                name="nom"
                class="form-control <?= !empty($errors['nom']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $nom) ?>"
                placeholder="Ex : Salle informatique A"
            >
            <?php if (!empty($errors['nom'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['nom']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="batiment">Bâtiment</label>
            <input
                type="text"
                id="batiment"
                name="batiment"
                class="form-control <?= !empty($errors['batiment']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $batiment) ?>"
                placeholder="Ex : Bâtiment Sciences"
            >
            <?php if (!empty($errors['batiment'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['batiment']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="capacite">Capacité (places)</label>
            <input
                type="number"
                id="capacite"
                name="capacite"
                min="1"
                max="1000"
                class="form-control <?= !empty($errors['capacite']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) $capacite) ?>"
                placeholder="Ex : 30"
            >
            <?php if (!empty($errors['capacite'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['capacite']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="type">Type de salle</label>
            <select id="type" name="type" class="form-control <?= !empty($errors['type']) ? 'is-invalid' : '' ?>">
                <?php foreach (\App\Model\TypeSalleEnum::cases() as $type): ?>
                    <option
                        value="<?= $type->value ?>"
                        <?= $typeVal === $type->value ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($type->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (!empty($errors['type'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['type']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input
                    type="checkbox"
                    name="active"
                    <?= $active ? 'checked' : '' ?>
                >
                <span>Salle active (disponible pour les réservations)</span>
            </label>
            <?php if (!empty($errors['active'])): ?>
                <div class="field-error"><?= htmlspecialchars($errors['active']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter la salle' ?>
            </button>
            <a href="/salles" class="btn btn-secondary">
                Annuler
            </a>
        </div>

    </form>
</div>