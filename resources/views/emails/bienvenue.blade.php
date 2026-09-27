<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: system-ui, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #f97316; padding: 30px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 24px; }
        .body { padding: 30px; }
        .body h2 { color: #1a1a1a; }
        .body p { color: #555; line-height: 1.6; }
        .btn { display: inline-block; background: #f97316; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 20px 0; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AK Recrutement</h1>
        </div>
        <div class="body">
            <h2>Bienvenue {{ $user->name }} ! 👋</h2>
            <p>Votre compte a été créé avec succès sur <strong>AK Recrutement</strong>.</p>
            <p>Vous êtes inscrit en tant que <strong>{{ ucfirst($user->role) }}</strong>.</p>
            @if($user->isCandidat())
                <p>Vous pouvez maintenant parcourir les offres d'emploi, postuler et suivre vos candidatures.</p>
            @else
                <p>Vous pouvez maintenant publier vos offres d'emploi et gérer vos recrutements.</p>
            @endif
            <a href="{{ config('app.url') }}" class="btn">Accéder à la plateforme</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AK Recrutement. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>