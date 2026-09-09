<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des réservations de salles</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="container">
        <h1>
            <a href="/salles" style="color: inherit; text-decoration: none;">Gestion Réservations</a>
        </h1>
        <nav>
            <a href="/salles">Salles</a>
            <a href="/reservations">Réservations</a>
        </nav>
    </div>
</header>

<main class="container">

    <?php if (!empty($flashMessages)): ?>
        <div class="flash-messages">
            <?php foreach ($flashMessages as $type => $messages): ?>
                <?php foreach ((array) $messages as $message): ?>
                    <div class="alert alert-<?= htmlspecialchars((string) $type) ?>">
                        <?= htmlspecialchars((string) $message) ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?= $contenu ?? '' ?>

</main>

</body>
</html>