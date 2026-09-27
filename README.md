# AK Recrutement

Plateforme de recrutement développée avec Laravel — mise en relation entre candidats et recruteurs, avec un espace d'administration complet.

## 🚀 Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure ta base de données dans `.env` (MySQL recommandé) :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ak_recrutement
DB_USERNAME=root
DB_PASSWORD=
```

Puis :
```bash
php artisan migrate --seed
php artisan storage:link
```

⚠️ **`storage:link` est obligatoire** — sans cette commande, les CV uploadés par les candidats ne seront pas accessibles.

Configure l'envoi d'email dans `.env` (exemple Gmail) :
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=ton-email@gmail.com
MAIL_PASSWORD="mot de passe d'application"
MAIL_FROM_ADDRESS="ton-email@gmail.com"
```

Lance le serveur et le worker de queue (nécessaire pour l'envoi d'emails en arrière-plan) :
```bash
php artisan serve
php artisan queue:work
```

## 👤 Comptes

Le compte administrateur est créé automatiquement par le seeder (voir `database/seeders/AdminSeeder.php`).

Les comptes candidat et recruteur s'inscrivent via `/inscription`.

## 🏗️ Architecture

- **Rôles** : `candidat`, `recruteur`, `admin` — gérés via middleware `role:` sur les routes
- **Sécurité** : mots de passe forts obligatoires (8+ caractères, majuscule, chiffre, symbole), comptes suspendables, CSRF protection native Laravel
- **Emails** : envoyés en file d'attente (`ShouldQueue`) — bienvenue, nouvelle candidature, changement de statut, message de contact
- **Base de données** : MySQL avec migrations versionnées

## 📂 Structure principale

```
app/Http/Controllers/    Contrôleurs (Admin, Candidat, Recruteur, Offre, Candidature, Message)
app/Http/Middleware/     CheckRole, CheckSuspendu, AdminMiddleware
app/Models/              User, Offre, Candidature, Message, Contact, Signalement
app/Mail/                4 mailables avec templates dans resources/views/emails
resources/views/         Vues Blade organisées par rôle (admin/, candidat/, recruteur/)
```

## ✅ Tests

```bash
php artisan test
```

Couvre : inscription, connexion, blocage des comptes suspendus, publication d'offre (recruteur uniquement), candidature (candidat uniquement).

## 🔒 Points de sécurité implémentés

- Un candidat ne peut pas accéder aux routes recruteur/admin et inversement (middleware `role`)
- Un compte suspendu est automatiquement déconnecté à chaque requête
- La messagerie vérifie qu'un lien légitime existe entre les deux utilisateurs avant d'autoriser l'accès à une conversation
- Un recruteur ne peut voir le profil complet d'un candidat que si celui-ci a postulé à l'une de ses offres
- Seul le propriétaire d'une offre peut la modifier ou la supprimer
