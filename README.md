# AK Recrutement

Plateforme de recrutement développée avec Laravel — mise en relation entre candidats et recruteurs, avec un espace d'administration complet.

## 🚀 Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build   # compile le CSS (Tailwind) et le JavaScript avec Vite
```

Pendant le développement, `npm run dev` recompile automatiquement à chaque modification.

Configure ta base de données dans `.env` (MySQL recommandé) :
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ak_recrutement
DB_USERNAME=root
DB_PASSWORD=
```

Renseigne l'adresse de l'administrateur (elle reçoit les messages du formulaire de contact) :
```
ADMIN_EMAIL="ton-email@gmail.com"
ADMIN_PASSWORD=          # facultatif : si vide, un mot de passe aléatoire est généré et affiché
```

Puis :
```bash
php artisan migrate --seed                  # crée les tables et le compte administrateur
php artisan db:seed --class=DemoSeeder      # facultatif : données de démonstration
```

Le seeder de démonstration crée 4 recruteurs, 8 candidats, 10 offres, 13 candidatures, une conversation et 3 messages de contact.
Tous les comptes de démo utilisent le mot de passe `Demo2026!` (par exemple `claire.martin@example.com` pour un recruteur, `lea.dubois@example.com` pour une candidate).
Il peut être relancé sans créer de doublons et refuse de s'exécuter en production.

Les CV sont stockés dans un dossier privé (`storage/app/private/cvs`) et ne sont jamais accessibles par une URL publique.

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

Le compte administrateur est créé par `php artisan migrate --seed` à partir de `ADMIN_EMAIL` et `ADMIN_PASSWORD` (voir `database/seeders/AdminSeeder.php`). Aucun mot de passe n'est écrit dans le code.

Les comptes candidat et recruteur s'inscrivent via `/inscription`. En cas d'oubli, le lien « Mot de passe oublié ? » de la page de connexion envoie un e-mail de réinitialisation (lien valable 60 minutes, utilisable une seule fois).

## 🏗️ Architecture

- **Rôles** : `candidat`, `recruteur`, `admin` — gérés via middleware `role:` sur les routes
- **Sécurité** : mots de passe forts obligatoires (8+ caractères, majuscule, chiffre, symbole), comptes suspendables, CSRF protection native Laravel
- **Emails** : envoyés en file d'attente (`ShouldQueue`) — bienvenue, nouvelle candidature, changement de statut, message de contact, réponse de l'admin, réinitialisation du mot de passe
- **MVC** : aucune requête SQL dans les vues. Les compteurs des barres latérales (partagées par toutes les pages) sont fournis par des View Composers (`app/View/Composers`)
- **Code partagé** : le changement de mot de passe et la suppression de compte des trois espaces sont dans le trait `app/Http/Controllers/Concerns/GereCompte.php`
- **Langue** : messages de validation en français (`lang/fr/validation.php`)
- **Base de données** : MySQL avec migrations versionnées
- **Front-end** : Tailwind CSS 4 et JavaScript compilés par Vite (`resources/css`, `resources/js`)
  - `menu.js` : menu burger sur mobile (navigation publique et barres latérales des espaces connectés), accessible au clavier (`aria-expanded`, touche Échap)
  - `recherche.js` : recherche d'offres en direct avec `fetch`, anti-rebond et annulation des requêtes obsolètes (`AbortController`) ; fonctionne aussi sans JavaScript (amélioration progressive)

## 📂 Structure principale

```
app/Http/Controllers/    Contrôleurs (Admin, Candidat, Recruteur, Offre, Candidature, Message, Auth)
app/Http/Controllers/Concerns/  Trait GereCompte (code partagé entre les espaces)
app/Http/Middleware/     CheckRole, CheckSuspendu
app/Models/              User, Offre, Candidature, Message, Contact
app/View/Composers/      Données des barres latérales admin et recruteur
app/Mail/                6 mailables avec templates dans resources/views/emails
database/seeders/        AdminSeeder (compte admin), DemoSeeder (données de démonstration)
resources/views/         Vues Blade organisées par rôle (admin/, candidat/, recruteur/)
```

## ✅ Tests

```bash
php artisan test
```

Couvre : inscription, connexion, blocage des comptes suspendus, publication d'offre (recruteur uniquement), candidature (candidat uniquement), et les tests de sécurité (`tests/Feature/SecuriteTest.php`) : limitation des tentatives de connexion, protection des CV, offres désactivées, robustesse des mots de passe, accès à l'administration.

## 🔒 Points de sécurité implémentés

- Un candidat ne peut pas accéder aux routes recruteur/admin et inversement (middleware `role`)
- Un compte suspendu est automatiquement déconnecté à chaque requête
- La messagerie vérifie qu'un lien légitime existe entre les deux utilisateurs avant d'autoriser l'accès à une conversation
- Un recruteur ne peut voir le profil complet d'un candidat que si celui-ci a postulé à l'une de ses offres
- Seul le propriétaire d'une offre peut la modifier ou la supprimer
- Les CV sont privés : seuls le candidat, l'admin et les recruteurs à qui il a postulé peuvent les télécharger
- Connexion, inscription et formulaire de contact limités à 5 tentatives par minute (protection contre la force brute et le spam)
- Une offre désactivée n'est plus visible ni accessible aux candidatures
- « Mot de passe oublié » : même message que l'adresse existe ou non (pas d'énumération des comptes), jeton à usage unique valable 60 minutes, limité à 5 demandes par minute
