<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Finitions et accessibilité des interfaces.
 */
class InterfacesTest extends TestCase
{
    use RefreshDatabase;

    private function candidatureAcceptee(): Candidature
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $candidat = User::factory()->create(['role' => 'candidat']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id]);

        return Candidature::create(['offre_id' => $offre->id, 'user_id' => $candidat->id, 'statut' => 'acceptee']);
    }

    public function test_les_mois_des_graphiques_sont_en_francais(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $moisAnglais = ['Jan', 'Feb', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        foreach (['/admin', '/admin/statistiques'] as $page) {
            $html = $this->actingAs($admin)->get($page)->assertOk()->getContent();

            $this->assertStringContainsString('>' . ucfirst(now()->translatedFormat('M')) . '<', $html);
            foreach ($moisAnglais as $mois) {
                $this->assertStringNotContainsString(">$mois<", $html, "Mois anglais « $mois » sur $page");
            }
        }
    }

    public function test_les_statuts_generes_ont_leurs_accents(): void
    {
        $candidature = $this->candidatureAcceptee();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertSame('Acceptée', $candidature->libelle_statut);
        $this->actingAs($admin)->get("/admin/utilisateurs/{$candidature->user_id}")
            ->assertOk()
            ->assertSee('Acceptée')
            ->assertDontSee('Acceptee');
    }

    public function test_l_admin_peut_changer_le_statut_d_une_candidature(): void
    {
        // Le formulaire existait sur la page admin mais renvoyait une erreur 403
        $candidature = $this->candidatureAcceptee();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch("/candidatures/{$candidature->id}/statut", ['statut' => 'refusee'])
            ->assertSessionHas('success');

        $this->assertSame('refusee', $candidature->fresh()->statut);
    }

    public function test_un_autre_recruteur_ne_peut_toujours_pas_changer_le_statut(): void
    {
        $candidature = $this->candidatureAcceptee();
        $autre = User::factory()->create(['role' => 'recruteur']);

        $this->actingAs($autre)->patch("/candidatures/{$candidature->id}/statut", ['statut' => 'refusee'])->assertForbidden();
    }

    public function test_aucun_emoji_n_est_affiche(): void
    {
        $candidature = $this->candidatureAcceptee();
        $pages = [
            [null, ['/', '/offres', '/connexion', '/inscription']],
            [User::find($candidature->user_id), ['/tableau-de-bord', '/mon-profil', '/mes-candidatures']],
            [$candidature->offre->recruteur, ['/tableau-de-bord', '/recruteur/offres', '/recruteur/candidatures', '/recruteur/profil', "/recruteur/candidats/{$candidature->user_id}"]],
            [User::factory()->create(['role' => 'admin']), ['/admin', '/admin/contacts']],
        ];

        foreach ($pages as [$user, $urls]) {
            foreach ($urls as $url) {
                $requete = $user ? $this->actingAs($user) : $this;
                $texte = strip_tags($requete->get($url)->assertOk()->getContent());
                // © est un caractère typographique, pas un emoji
                preg_match_all('/(?!\x{A9})\p{Extended_Pictographic}/u', $texte, $emojis);
                $this->assertEmpty($emojis[0], "Emoji(s) sur $url : " . implode(' ', $emojis[0]));
            }
        }
    }

    public function test_les_champs_de_connexion_et_d_inscription_ont_un_label_lie(): void
    {
        $champs = ['/connexion' => ['email', 'password'], '/inscription' => ['name', 'email', 'password', 'password_confirmation', 'role']];

        foreach ($champs as $page => $noms) {
            $html = $this->get($page)->assertOk()->getContent();
            foreach ($noms as $nom) {
                $this->assertStringContainsString("<label for=\"$nom\"", $html, "Label de « $nom » sur $page");
                $this->assertMatchesRegularExpression("/id=\"$nom\"\\s+name=\"$nom\"/", $html, "Champ « $nom » sur $page");
            }
        }
    }

    public function test_les_barres_laterales_partagent_le_meme_style_et_signalent_la_page_active(): void
    {
        $candidature = $this->candidatureAcceptee();
        $users = [User::find($candidature->user_id), $candidature->offre->recruteur, User::factory()->create(['role' => 'admin'])];

        foreach ($users as $user) {
            $html = $this->actingAs($user)->get($user->isAdmin() ? '/admin' : '/tableau-de-bord')->getContent();
            $barre = substr($html, strpos($html, '<aside'), strpos($html, '</aside>') - strpos($html, '<aside'));

            $this->assertStringContainsString('aria-current="page"', $barre, $user->role);
            $this->assertStringContainsString('<nav aria-label=', $barre, $user->role);
            $this->assertStringContainsString('px-3 py-2.5 rounded-lg text-sm font-medium', $barre, $user->role);
        }
    }

    public function test_les_tableaux_ont_un_indicateur_de_defilement(): void
    {
        $candidature = $this->candidatureAcceptee();
        $admin = User::factory()->create(['role' => 'admin']);

        foreach (['/admin/utilisateurs', '/admin/offres', '/admin/candidatures'] as $page) {
            $this->actingAs($admin)->get($page)->assertOk()
                ->assertSee('data-tableau-defilant', false)
                ->assertSee('Faites glisser le tableau');
        }
        $this->actingAs($candidature->offre->recruteur)->get('/recruteur/candidatures')->assertOk()
            ->assertSee('data-tableau-defilant', false);
    }

    public function test_les_pages_claires_utilisent_l_orange_accessible(): void
    {
        $candidature = $this->candidatureAcceptee();

        // Page visiteur d'abord (/connexion redirige une fois connecté)
        $this->get('/connexion')->assertSee('<body class="fond-clair', false);
        $this->actingAs(User::find($candidature->user_id))->get('/tableau-de-bord')->assertSee('<body class="fond-clair', false);
        // L'espace admin (fond sombre) garde l'orange vif
        $this->actingAs(User::factory()->create(['role' => 'admin']))->get('/admin')->assertDontSee('fond-clair');
    }

    public function test_chaque_page_a_un_titre_h1(): void
    {
        $candidature = $this->candidatureAcceptee();
        $pages = [
            [User::find($candidature->user_id), ['/tableau-de-bord', '/mon-profil', '/mes-candidatures', '/parametres']],
            [$candidature->offre->recruteur, ['/tableau-de-bord', '/recruteur/profil', '/recruteur/offres', '/recruteur/candidatures', '/recruteur/parametres']],
        ];

        foreach ($pages as [$user, $urls]) {
            foreach ($urls as $url) {
                $html = $this->actingAs($user)->get($url)->assertOk()->getContent();
                $this->assertSame(1, substr_count($html, '<h1'), "Il faut exactement un <h1> sur $url");
            }
        }
    }

    public function test_aucune_vue_ne_masque_le_focus_clavier(): void
    {
        $fichiers = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views')));

        foreach ($fichiers as $fichier) {
            if (str_ends_with($fichier, '.blade.php')) {
                $this->assertStringNotContainsString('outline-hidden', file_get_contents($fichier), "Focus masqué dans $fichier");
                $this->assertStringNotContainsString('outline-none', file_get_contents($fichier), "Focus masqué dans $fichier");
            }
        }
    }
}
