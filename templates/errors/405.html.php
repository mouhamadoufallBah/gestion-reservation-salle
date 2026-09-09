<div class="card" style="text-align: center; max-width: 600px; margin: 60px auto;">
    <h1 style="color: #f59e0b; font-size: 54px; margin-bottom: 10px;">405</h1>
    <h2 style="margin-bottom: 15px;">Méthode non autorisée</h2>
    <p style="color: #6b7280; margin-bottom: 25px;">
        Cette méthode HTTP n'est pas autorisée pour cette ressource.
        <?php if (!empty($allowedMethods)): ?>
            <br><small>Méthodes autorisées : <?= htmlspecialchars(implode(', ', $allowedMethods)) ?></small>
        <?php endif; ?>
    </p>

    <div style="margin-top: 20px;">
        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>