<div class="card" style="text-align: center; max-width: 600px; margin: 60px auto;">
    <h1 style="color: #2563eb; font-size: 54px; margin-bottom: 10px;">404</h1>
    <h2 style="margin-bottom: 15px;">Page introuvable</h2>
    <p style="color: #6b7280; margin-bottom: 25px;">
        <?= htmlspecialchars($message ?? "La ressource ou la page que vous recherchez n'existe pas.") ?>
    </p>

    <div style="margin-top: 20px;">
        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>