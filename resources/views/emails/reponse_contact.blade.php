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
        .reponse { color: #333; line-height: 1.6; white-space: pre-line; margin: 20px 0; }
        .message-box { background: #f9f9f9; border-left: 4px solid #f97316; padding: 16px; margin: 20px 0; color: #777; line-height: 1.6; white-space: pre-line; font-size: 14px; }
        .footer { background: #f5f5f5; padding: 20px; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AK Recrutement</h1>
        </div>
        <div class="body">
            <h2>Bonjour {{ $contact->nom }},</h2>
            <p>Merci de nous avoir contactés. Voici notre réponse à votre message « {{ $contact->sujet }} » :</p>
            {{-- white-space: pre-line conserve les retours à la ligne sans désactiver l'échappement de Blade --}}
            <div class="reponse">{{ $reponse }}</div>
            <p><strong>Votre message du {{ $contact->created_at->format('d/m/Y à H:i') }} :</strong></p>
            <div class="message-box">{{ $contact->message }}</div>
            <p>L'équipe AK Recrutement</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AK Recrutement. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
