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
        .info-box { background: #fff7ed; border: 1px solid #f97316; border-radius: 8px; padding: 16px; margin: 20px 0; }
        .info-box p { margin: 6px 0; color: #333; }
        .message-box { background: #f9f9f9; border-left: 4px solid #f97316; padding: 16px; margin: 20px 0; color: #333; line-height: 1.6; }
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
            <h2>Nouveau message de contact 📬</h2>
            <p>Vous avez reçu un nouveau message via le formulaire de contact.</p>
            <div class="info-box">
                <p><strong>Nom :</strong> {{ $contact->nom }}</p>
                <p><strong>Email :</strong> {{ $contact->email }}</p>
                <p><strong>Sujet :</strong> {{ $contact->sujet }}</p>
                <p><strong>Date :</strong> {{ $contact->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <p><strong>Message :</strong></p>
            <div class="message-box">{{ $contact->message }}</div>
            <a href="{{ config('app.url') }}/admin/contacts" class="btn">Voir dans l'admin</a>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AK Recrutement. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>