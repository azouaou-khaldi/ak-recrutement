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
        .status-ok { background: #f0fdf4; border: 1px solid #22c55e; border-radius: 8px; padding: 16px; margin: 20px 0; text-align: center; color: #16a34a; font-size: 18px; font-weight: bold; }
        .status-no { background: #fef2f2; border: 1px solid #ef4444; border-radius: 8px; padding: 16px; margin: 20px 0; text-align: center; color: #dc2626; font-size: 18px; font-weight: bold; }
        .info-box { background: #fff7ed; border: 1px solid #f97316; border-radius: 8px; padding: 16px; margin: 20px 0; }
        .info-box p { margin: 6px 0; color: #333; }
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
            <h2>Mise à jour de votre candidature</h2>
            <p>Bonjour <strong>{{ $candidature->candidat->name }}</strong>,</p>
            <p>Le statut de votre candidature pour le poste <strong>{{ $candidature->offre->titre }}</strong> chez <strong>{{ $candidature->offre->entreprise }}</strong> a été mis à jour.</p>

            @if($candidature->statut === 'acceptee')
                <div class="status-ok">✓ Votre candidature a été acceptée !</div>
                <p>Félicitations ! Le recruteur a accepté votre candidature. Vous pouvez le contacter via la messagerie pour convenir d'un entretien.</p>
            @else
                <div class="status-no">✗ Votre candidature n'a pas été retenue</div>
                <p>Malheureusement votre candidature n'a pas été retenue cette fois. Ne vous découragez pas, d'autres opportunités vous attendent !</p>
            @endif

            <div class="info-box">
                <p><strong>Poste :</strong> {{ $candidature->offre->titre }}</p>
                <p><strong>Entreprise :</strong> {{ $candidature->offre->entreprise }}</p>
                <p><strong>Lieu :</strong> {{ $candidature->offre->lieu }}</p>
            </div>

            <a href="{{ config('app.url') }}/mes-candidatures" class="btn">Voir mes candidatures</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AK Recrutement. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>