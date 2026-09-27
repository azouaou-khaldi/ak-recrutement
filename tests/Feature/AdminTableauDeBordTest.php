<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Message;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTableauDeBordTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_la_repartition_des_utilisateurs_correspond_au_total(): void
    {
        User::factory()->count(3)->create(['role' => 'candidat']);
        User::factory()->count(2)->create(['role' => 'recruteur']);

        // 3 + 2 + 1 administrateur = 6 : la légende doit additionner au total affiché
        $this->actingAs($this->admin)->get('/admin')->assertOk()
            ->assertSeeInOrder(['Candidats — 3', 'Recruteurs — 2', 'Administrateurs — 1']);
    }

    public function test_une_evolution_nulle_affiche_un_message_neutre(): void
    {
        $html = $this->actingAs($this->admin)->get('/admin')->assertOk()->getContent();

        $this->assertStringContainsString('Aucune nouvelle offre cette semaine', $html);
        $this->assertStringContainsString('Aucune candidature ce mois', $html);
        $this->assertStringNotContainsString('+0', $html);
    }

    public function test_une_evolution_positive_reste_affichee(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        Offre::factory()->count(2)->create(['user_id' => $recruteur->id]);

        $this->actingAs($this->admin)->get('/admin')->assertOk()->assertSee('+2 cette semaine');
    }

    public function test_les_cartes_de_statistiques_menent_a_leurs_pages(): void
    {
        $html = $this->actingAs($this->admin)->get('/admin')->assertOk()->getContent();

        foreach (['admin.users', 'admin.offres', 'admin.candidatures', 'admin.contacts'] as $route) {
            $this->assertMatchesRegularExpression('/<a href="' . preg_quote(route($route), '/') . '" class="group bg-darkCard/', $html, $route);
        }
        $this->assertStringNotContainsString('cursor-pointer', $html);
    }

    public function test_la_page_statistiques_n_existe_plus(): void
    {
        $this->actingAs($this->admin)->get('/admin/statistiques')->assertNotFound();
        $this->actingAs($this->admin)->get('/admin')->assertDontSee('Statistiques');
    }

    public function test_la_popup_de_suppression_detaille_ce_qui_sera_supprime_pour_un_recruteur(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur', 'name' => 'Claire Martin']);
        $offres = Offre::factory()->count(2)->create(['user_id' => $recruteur->id]);
        $candidats = User::factory()->count(3)->create(['role' => 'candidat']);
        foreach ($candidats as $candidat) {
            Candidature::create(['offre_id' => $offres[0]->id, 'user_id' => $candidat->id]);
        }
        Message::create(['sender_id' => $recruteur->id, 'receiver_id' => $candidats[0]->id, 'contenu' => 'Bonjour']);

        foreach (['/admin/utilisateurs', "/admin/utilisateurs/{$recruteur->id}"] as $page) {
            $this->actingAs($this->admin)->get($page)->assertOk()
                ->assertSee('data-confirmation-titre="Supprimer le compte de Claire Martin ?"', false)
                ->assertSee('2 offres publiées')
                ->assertSee('3 candidatures reçues sur ces offres')
                ->assertSee('1 message de messagerie');
        }
    }

    public function test_la_popup_de_suppression_detaille_ce_qui_sera_supprime_pour_un_candidat(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat', 'cv_path' => 'cvs/cv.pdf']);
        $offre = Offre::factory()->create();
        Candidature::create(['offre_id' => $offre->id, 'user_id' => $candidat->id]);

        $this->actingAs($this->admin)->get("/admin/utilisateurs/{$candidat->id}")->assertOk()
            ->assertSee('1 candidature')
            ->assertSee('son CV (fichier supprimé du serveur)')
            ->assertSee('0 message de messagerie')
            ->assertSee('id="modale-confirmation"', false);
    }

    public function test_le_cv_est_efface_du_disque_quand_le_compte_est_supprime(): void
    {
        Storage::fake('local');
        $candidat = User::factory()->create(['role' => 'candidat']);
        $this->actingAs($candidat)->post('/mon-profil/cv', ['cv' => UploadedFile::fake()->create('cv.pdf', 50, 'application/pdf')]);
        $chemin = $candidat->fresh()->cv_path;
        Storage::disk('local')->assertExists($chemin);

        $this->actingAs($this->admin)->delete("/admin/utilisateurs/{$candidat->id}")->assertSessionHas('success');

        $this->assertModelMissing($candidat);
        Storage::disk('local')->assertMissing($chemin);
    }

    public function test_les_boutons_d_action_font_44px_sur_mobile(): void
    {
        User::factory()->create(['role' => 'candidat']);

        $html = $this->actingAs($this->admin)->get('/admin/utilisateurs')->assertOk()->getContent();

        preg_match_all('/<(?:a|button) [^>]*class="([^"]*)"[^>]*>\s*(Voir|Suspendre|Supprimer|Filtrer)\s*</', $html, $boutons);
        $this->assertNotEmpty($boutons[1]);
        foreach ($boutons[1] as $i => $classes) {
            $this->assertStringContainsString('min-h-11', $classes, "Bouton « {$boutons[2][$i]} » trop petit sur mobile");
        }
    }
}
