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

Puis :
```bash
php artisan migrate --seed
```

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

Le compte administrateur est créé automatiquement par le seeder (voir `database/seeders/AdminSeeder.php`).

Les comptes candidat et recruteur s'inscrivent via `/inscription`.

## 🏗️ Architecture

- **Rôles** : `candidat`, `recruteur`, `admin` — gérés via middleware `role:` sur les routes
- **Sécurité** : mots de passe forts obligatoires (8+ caractères, majuscule, chiffre, symbole), comptes suspendables, CSRF protection native Laravel
- **Emails** : envoyés en file d'attente (`ShouldQueue`) — bienvenue, nouvelle candidature, changement de statut, message de contact
- **Base de données** : MySQL avec migrations versionnées
- **Front-end** : Tailwind CSS 4 et JavaScript compilés par Vite (`resources/css`, `resources/js`)
  - `menu.js` : menu burger sur mobile (navigation publique et barres latérales des espaces connectés), accessible au clavier (`aria-expanded`, touche Échap)
  - `recherche.js` : recherche d'offres en direct avec `fetch`, anti-rebond et annulation des requêtes obsolètes (`AbortController`) ; fonctionne aussi sans JavaScript (amélioration progressive)

## 📂 Structure principale

```
app/Http/Controllers/    Contrôleurs (Admin, Candidat, Recruteur, Offre, Candidature, Message)
app/Http/Middleware/     CheckRole, CheckSuspendu
app/Models/              User, Offre, Candidature, Message, Contact
app/Mail/                4 mailables avec templates dans resources/views/emails
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
