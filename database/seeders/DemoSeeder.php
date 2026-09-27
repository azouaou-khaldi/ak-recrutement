<?php

namespace Database\Seeders;

use App\Models\Candidature;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Données de démonstration réalistes pour présenter l'application.
 *
 *   php artisan db:seed --class=DemoSeeder
 *
 * - Tous les comptes de démo ont le mot de passe : Demo2026!
 * - Les adresses sont en @example.com (domaine réservé : aucun e-mail n'atteint une vraie personne)
 * - Peut être relancé sans créer de doublons
 * - Refuse de s'exécuter en production
 */
class DemoSeeder extends Seeder
{
    public const MOT_DE_PASSE = 'Demo2026!';

    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('DemoSeeder ne doit pas être lancé en production.');
        }

        // Permet de renseigner created_at (dates réparties sur plusieurs mois pour les graphiques)
        Model::unguarded(function () {
            $recruteurs = $this->creerRecruteurs();
            $offres = $this->creerOffres($recruteurs);
            $candidats = $this->creerCandidats();
            $this->creerCandidatures($offres, $candidats);
            $this->creerMessages($offres, $candidats);
            $this->creerContacts();
        });

        $this->command?->info('Démo prête. Comptes (mot de passe : ' . self::MOT_DE_PASSE . ') :');
        $this->command?->table(['Rôle', 'E-mail'], [
            ['Recruteur', 'claire.martin@example.com'],
            ['Recruteur', 'julien.moreau@example.com'],
            ['Candidat', 'lea.dubois@example.com'],
            ['Candidat', 'thomas.nguyen@example.com'],
        ]);
    }

    private function joursAvant(int $jours)
    {
        return now()->subDays($jours)->setTime(rand(8, 18), rand(0, 59));
    }

    /** @return array<string, User> */
    private function creerRecruteurs(): array
    {
        $donnees = [
            'claire' => ['Claire Martin', 'claire.martin@example.com', 'NovaTech Solutions', 'Informatique / ESN', '50-200',
                'ESN parisienne spécialisée dans le développement d\'applications web sur mesure pour les PME et les collectivités.', 'https://www.novatech-solutions.example.com', 170],
            'julien' => ['Julien Moreau', 'julien.moreau@example.com', 'Maison Lemoine', 'Hôtellerie - Restauration', '10-50',
                'Groupe de trois restaurants lyonnais, cuisine de saison et produits locaux.', 'https://www.maison-lemoine.example.com', 150],
            'sophie' => ['Sophie Bernard', 'sophie.bernard@example.com', 'BatiSud Construction', 'BTP', '200-500',
                'Entreprise générale du bâtiment intervenant sur la rénovation énergétique en région PACA.', 'https://www.batisud.example.com', 120],
            'karim'  => ['Karim Haddad', 'karim.haddad@example.com', 'Pharmalys', 'Santé', '50-200',
                'Laboratoire de distribution pharmaceutique basé à Lille, en forte croissance.', 'https://www.pharmalys.example.com', 90],
        ];

        $recruteurs = [];
        foreach ($donnees as $cle => [$nom, $email, $entreprise, $secteur, $taille, $description, $site, $jours]) {
            $recruteurs[$cle] = User::firstOrCreate(['email' => $email], [
                'name' => $nom, 'password' => self::MOT_DE_PASSE, 'role' => 'recruteur',
                'entreprise' => $entreprise, 'secteur' => $secteur, 'taille_entreprise' => $taille,
                'description_entreprise' => $description, 'site_web' => $site, 'telephone' => '01 ' . rand(40, 79) . ' 12 34 56',
                'created_at' => $this->joursAvant($jours),
            ]);
        }

        return $recruteurs;
    }

    /** @return array<string, Offre> */
    private function creerOffres(array $r): array
    {
        $donnees = [
            'dev_laravel' => ['claire', 'Développeur web PHP / Laravel', 'Paris 9e', 'CDI', '38 000 - 45 000 € / an', 'PHP, Laravel, MySQL, Git',
                "Au sein d'une équipe de 6 développeurs, vous participerez à la conception et à la maintenance d'applications web pour nos clients.\n\nVos missions : développer de nouvelles fonctionnalités, écrire des tests, participer aux revues de code.", 160],
            'alternance' => ['claire', 'Alternant développeur web et web mobile', 'Paris 9e', 'Alternance', 'Selon grille légale', 'HTML, CSS, JavaScript, PHP',
                "Vous préparez le titre DWWM et souhaitez rejoindre une équipe bienveillante ? Vous serez accompagné par un tuteur et travaillerez sur des projets réels.", 140],
            'front' => ['claire', 'Développeur front-end JavaScript', 'Télétravail partiel - Paris', 'CDD', '3 200 € brut / mois', 'JavaScript, Tailwind CSS, accessibilité',
                "CDD de 12 mois pour la refonte de l'interface d'un portail public, avec une forte attention portée à l'accessibilité (RGAA).", 45],
            'serveur' => ['julien', 'Serveur / Serveuse', 'Lyon 2e', 'CDI', '1 900 € brut / mois + pourboires', 'Service en salle, anglais courant',
                "Rejoignez l'équipe de notre restaurant de la Presqu'île. Service midi et soir, deux jours de repos consécutifs.", 130],
            'commis' => ['julien', 'Commis de cuisine', 'Lyon 6e', 'CDD', '1 850 € brut / mois', 'CAP cuisine, hygiène HACCP',
                "Renfort de la brigade pour la saison. Vous assisterez le chef de partie dans la préparation des plats.", 60],
            'chef_chantier' => ['sophie', 'Chef de chantier rénovation énergétique', 'Marseille', 'CDI', '42 000 € / an', 'Gestion d\'équipe, isolation, lecture de plans',
                "Vous encadrez une équipe de 5 à 8 compagnons sur des chantiers de rénovation de logements collectifs.", 110],
            'stage_btp' => ['sophie', 'Stage assistant conducteur de travaux', 'Aix-en-Provence', 'Stage', 'Gratification légale', 'Autocad, suivi de planning',
                "Stage de 6 mois pour accompagner nos conducteurs de travaux dans le suivi des chantiers et la relation avec les sous-traitants.", 30],
            'preparateur' => ['karim', 'Préparateur de commandes', 'Lille - Lesquin', 'CDI', '1 950 € brut / mois', 'CACES 1, rigueur',
                "Préparation des commandes à destination des pharmacies, dans le respect des règles de traçabilité.", 80],
            'data' => ['karim', 'Analyste de données', 'Lille', 'Freelance', '450 € / jour', 'SQL, Python, Power BI',
                "Mission de 6 mois pour mettre en place des tableaux de bord de suivi des ventes et des stocks.", 20],
            'ancienne' => ['karim', 'Assistant logistique', 'Lille', 'CDD', '1 900 € brut / mois', 'Pack Office',
                "Offre pourvue : conservée pour l'historique.", 175, false],
        ];

        $offres = [];
        foreach ($donnees as $cle => $d) {
            [$recruteur, $titre, $lieu, $contrat, $salaire, $competences, $description, $jours] = $d;
            $offres[$cle] = Offre::firstOrCreate(['user_id' => $r[$recruteur]->id, 'titre' => $titre], [
                'entreprise' => $r[$recruteur]->entreprise, 'lieu' => $lieu, 'type_contrat' => $contrat,
                'salaire' => $salaire, 'competences_requises' => $competences, 'description' => $description,
                'active' => $d[8] ?? true, 'created_at' => $this->joursAvant($jours),
            ]);
        }

        return $offres;
    }

    /** @return array<string, User> */
    private function creerCandidats(): array
    {
        $donnees = [
            'lea'     => ['Léa Dubois', 'Développeuse web junior', 'Paris', 'Immédiate', 'Moins d\'1 an', 'PHP, Laravel, JavaScript, Tailwind CSS, Git',
                'En reconversion après une formation DWWM, je cherche mon premier poste de développeuse.', 150],
            'thomas'  => ['Thomas Nguyen', 'Développeur front-end', 'Montreuil', 'Sous 1 mois', '3 ans', 'JavaScript, React, accessibilité, Figma',
                'Développeur front-end attaché à l\'accessibilité et aux interfaces soignées.', 140],
            'ines'    => ['Inès Benali', 'Alternante développeuse web', 'Saint-Denis', 'Septembre', 'Débutante', 'HTML, CSS, JavaScript',
                'Je prépare le titre DWWM et je recherche une alternance dans une équipe web.', 100],
            'hugo'    => ['Hugo Lefèvre', 'Serveur', 'Lyon', 'Immédiate', '5 ans', 'Service en salle, anglais, conseil vins',
                'Serveur expérimenté en brasserie et restaurant gastronomique.', 120],
            'camille' => ['Camille Roux', 'Commis de cuisine', 'Villeurbanne', 'Immédiate', '1 an', 'CAP cuisine, HACCP, pâtisserie',
                'Titulaire d\'un CAP cuisine, motivée pour intégrer une brigade.', 55],
            'mehdi'   => ['Mehdi Aït-Ali', 'Chef de chantier', 'Marseille', 'Sous 3 mois', '8 ans', 'Encadrement, isolation thermique, sécurité',
                'Chef d\'équipe dans le second œuvre, je souhaite évoluer vers un poste de chef de chantier.', 95],
            'manon'   => ['Manon Petit', 'Étudiante en BTS bâtiment', 'Aix-en-Provence', 'Janvier', 'Stage de 2 mois', 'Autocad, Excel',
                'Étudiante en BTS, je recherche un stage de fin d\'études.', 25],
            'lucas'   => ['Lucas Girard', 'Préparateur de commandes', 'Roubaix', 'Immédiate', '2 ans', 'CACES 1 et 3, gestion de stock',
                'Préparateur de commandes rigoureux, habitué aux environnements réglementés.', 70],
        ];

        $candidats = [];
        foreach ($donnees as $cle => [$nom, $poste, $ville, $dispo, $experience, $competences, $aPropos, $jours]) {
            $email = Str::slug($nom, '.') . '@example.com'; // « Léa Dubois » → lea.dubois@example.com
            $candidats[$cle] = User::firstOrCreate(['email' => $email], [
                'name' => $nom, 'password' => self::MOT_DE_PASSE, 'role' => 'candidat',
                'titre_poste' => $poste, 'ville' => $ville, 'disponibilite' => $dispo, 'experience' => $experience,
                'competences' => $competences, 'a_propos' => $aPropos, 'telephone' => '06 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                'created_at' => $this->joursAvant($jours),
            ]);
        }

        return $candidats;
    }

    private function creerCandidatures(array $o, array $c): void
    {
        $donnees = [
            ['lea', 'dev_laravel', 'acceptee', 'Votre stack correspond exactement à ma formation, je serais ravie d\'échanger avec vous.', 120],
            ['lea', 'front', 'en_attente', null, 30],
            ['thomas', 'front', 'en_attente', 'L\'accessibilité est au cœur de mon travail depuis trois ans.', 25],
            ['thomas', 'dev_laravel', 'refusee', null, 110],
            ['ines', 'alternance', 'acceptee', 'Je suis disponible pour un entretien à tout moment.', 90],
            ['ines', 'dev_laravel', 'en_attente', null, 12],
            ['hugo', 'serveur', 'acceptee', 'J\'ai travaillé cinq ans dans une brasserie de la place Bellecour.', 100],
            ['camille', 'commis', 'en_attente', 'Très motivée pour rejoindre votre brigade cette saison.', 40],
            ['camille', 'serveur', 'refusee', null, 50],
            ['mehdi', 'chef_chantier', 'en_attente', 'Huit ans d\'expérience sur des chantiers de rénovation.', 70],
            ['manon', 'stage_btp', 'en_attente', 'Mon stage doit débuter en janvier, pour une durée de 6 mois.', 15],
            ['lucas', 'preparateur', 'acceptee', null, 60],
            ['lucas', 'data', 'refusee', null, 10],
        ];

        foreach ($donnees as [$candidat, $offre, $statut, $message, $jours]) {
            Candidature::firstOrCreate(['user_id' => $c[$candidat]->id, 'offre_id' => $o[$offre]->id], [
                'statut' => $statut, 'message' => $message, 'created_at' => $this->joursAvant($jours),
            ]);
        }
    }

    private function creerMessages(array $o, array $c): void
    {
        $claire = $o['dev_laravel']->recruteur;
        $lea = $c['lea'];

        $dejaPresent = Message::where('sender_id', $lea->id)->where('receiver_id', $claire->id)->exists();
        if ($dejaPresent) {
            return;
        }

        $conversation = [
            [$lea, $claire, 'Bonjour Madame Martin, je vous remercie pour votre retour positif. Quand pourrions-nous nous rencontrer ?', 118],
            [$claire, $lea, 'Bonjour Léa, avec plaisir ! Êtes-vous disponible jeudi à 14 h dans nos locaux ?', 117],
            [$lea, $claire, 'Jeudi 14 h me convient parfaitement. À jeudi !', 117],
        ];

        foreach ($conversation as [$de, $a, $contenu, $jours]) {
            Message::create([
                'sender_id' => $de->id, 'receiver_id' => $a->id, 'offre_id' => $o['dev_laravel']->id,
                'contenu' => $contenu, 'lu' => true, 'created_at' => $this->joursAvant($jours),
            ]);
        }
    }

    private function creerContacts(): void
    {
        $donnees = [
            ['Nathalie Garnier', 'nathalie.garnier@example.com', 'Publier des offres pour mon entreprise',
                "Bonjour,\nje dirige une petite agence de communication à Nantes. Comment créer un compte recruteur ?\nMerci", false, null, 3],
            ['Paul Mercier', 'paul.mercier@example.com', 'Problème de connexion',
                'Bonjour, je n\'arrive plus à me connecter depuis hier, que dois-je faire ?', true,
                "Bonjour Paul,\n\nvous pouvez utiliser le lien « Mot de passe oublié » sur la page de connexion pour choisir un nouveau mot de passe.\n\nL'équipe AK Recrutement", 9],
            ['Agence Tremplin Emploi', 'contact@example.com', 'Proposition de partenariat',
                'Nous accompagnons des personnes en reconversion et aimerions présenter votre plateforme à nos bénéficiaires.', true, null, 20],
        ];

        foreach ($donnees as [$nom, $email, $sujet, $message, $lu, $reponse, $jours]) {
            Contact::firstOrCreate(['email' => $email, 'sujet' => $sujet], [
                'nom' => $nom, 'message' => $message, 'lu' => $lu,
                'reponse' => $reponse, 'repondu_le' => $reponse ? $this->joursAvant($jours - 1) : null,
                'created_at' => $this->joursAvant($jours),
            ]);
        }
    }
}
