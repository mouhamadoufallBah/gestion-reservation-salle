<?php $salle = $salle ?? null; ?>

<?php if ($salle): ?>

    <h1>Détail de la salle</h1>

    <div>

        <h2>
            <?= htmlspecialchars($salle->nom) ?>
        </h2>

        <p>
            <strong>ID :</strong>
            <?= $salle->id ?>
        </p>

        <p>
            <strong>Bâtiment :</strong>
            <?= htmlspecialchars($salle->batiment) ?>
        </p>

        <p>
            <strong>Capacité :</strong>
            <?= $salle->capacite ?> places
        </p>

        <p>
            <strong>Type :</strong>
            <?= htmlspecialchars($salle->type->value) ?>
        </p>

        <p>
            <strong>Statut :</strong>
            <?= $salle->active ? 'Active' : 'Inactive' ?>
        </p>

        <a href="/salles/<?= $salle->id ?>/edit">
            Modifier
        </a>

        <a href="/salles">
            Retour aux salles
        </a>

    </div>

<?php else: ?>

    <h1>Salle introuvable</h1>

    <a href="/salles">
        Retour aux salles
    </a>

<?php endif; ?>