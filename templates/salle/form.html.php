<?php $salle = $salle ?? null; ?>

<h1>
    <?= $salle ? 'Modifier la salle' : 'Ajouter une salle' ?>
</h1>

<form
    method="POST"
    action="<?= $salle ? '/salles/' . $salle->id . '/edit' : '/salles' ?>"
>

    <div>
        <label for="nom">Nom</label>

        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($salle->nom ?? '') ?>"
        >
    </div>

    <div>
        <label for="batiment">Bâtiment</label>

        <input
            type="text"
            id="batiment"
            name="batiment"
            value="<?= htmlspecialchars($salle->batiment ?? '') ?>"
        >
    </div>

    <div>
        <label for="capacite">Capacité</label>

        <input
            type="number"
            id="capacite"
            name="capacite"
            value="<?= $salle->capacite ?? '' ?>"
        >
    </div>

    <div>
        <label for="type">Type</label>

        <select id="type" name="type">

            <?php foreach (\App\Model\TypeSalleEnum::cases() as $type): ?>

                <option
                    value="<?= $type->value ?>"
                    <?= isset($salle) && $salle?->type === $type ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($type->value) ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <div>
        <label>
            <input
                type="checkbox"
                name="active"
                <?= ($salle->active ?? true) ? 'checked' : '' ?>
            >

            Active
        </label>
    </div>

    <button type="submit">
        <?= $salle ? 'Modifier' : 'Ajouter' ?>
    </button>

</form>

<a href="/salles">
    Retour aux salles
</a>