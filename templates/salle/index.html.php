<h1>Liste des salles</h1>

<a href="/salles/create">
    Ajouter une salle
</a>

<hr>

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
                    Aucune salle disponible.
                </td>
            </tr>

        <?php else: ?>

            <?php foreach ($salles as $salle): ?>

                <tr>
                    <td>
                        <?= $salle->id ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($salle->nom) ?>
                    </td>

                    <td>
                        <?= htmlspecialchars($salle->batiment) ?>
                    </td>

                    <td>
                        <?= $salle->capacite ?> places
                    </td>

                    <td>
                        <?= htmlspecialchars($salle->type->value) ?>
                    </td>

                    <td>
                        <?= $salle->active ? 'Active' : 'Inactive' ?>
                    </td>

                    <td>
                        <a href="/salles/<?= $salle->id ?>">
                            Voir
                        </a>

                        <a href="/salles/<?= $salle->id ?>/edit">
                            Modifier
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    </tbody>



</table>