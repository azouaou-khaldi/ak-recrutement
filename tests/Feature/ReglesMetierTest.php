<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Message;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Règles métier du cahier des charges qui n'étaient pas encore couvertes (RG01, RG08, RG10, RG12, RG13).
 */
class ReglesMetierTest extends TestCase
{
    use RefreshDatabase;

    // RG01 : le rôle administrateur n'est jamais proposé à l'inscription
    public function test_rg01_on_ne_peut_pas_s_inscrire_en_tant_qu_administrateur(): void
    {
        Mail::fake();

        $this->post('/inscription', [
            'name' => 'Pirate',
            'email' => 'pirate@example.com',
            'password' => 'MotDePasse1!',
            'password_confirmation' => 'MotDePasse1!',
            'role' => 'admin',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'pirate@example.com']);
        $this->get('/inscription')->assertDontSee('value="admin"', false);
    }

    // RG08 : pas de message sans candidature ni conversation déjà ouverte
    public function test_rg08_la_messagerie_est_reservee_aux_utilisateurs_lies(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $candidat = User::factory()->create(['role' => 'candidat']);

        // Aucun lien : refusé
        $this->actingAs($candidat)->post("/messages/{$recruteur->id}", ['contenu' => 'Bonjour'])->assertStatus(403);
        $this->assertSame(0, Message::count());

        // Après une candidature : autorisé dans les deux sens
        $offre = Offre::factory()->create(['user_id' => $recruteur->id]);
        Candidature::create(['offre_id' => $offre->id, 'user_id' => $candidat->id]);

        $this->actingAs($candidat)->post("/messages/{$recruteur->id}", ['contenu' => 'Bonjour'])->assertRedirect();
        $this->actingAs($recruteur)->post("/messages/{$candidat->id}", ['contenu' => 'Bonjour à vous'])->assertRedirect();
        $this->assertSame(2, Message::count());

        // Un autre recruteur sans lien reste bloqué
        $autreRecruteur = User::factory()->create(['role' => 'recruteur']);
        $this->actingAs($autreRecruteur)->post("/messages/{$candidat->id}", ['contenu' => 'Bonjour'])->assertStatus(403);
    }

    // RG10 : CV au format PDF, DOC ou DOCX, 5 Mo maximum
    public function test_rg10_le_cv_doit_etre_un_pdf_doc_ou_docx_de_5_mo_maximum(): void
    {
        Storage::fake('local');
        $candidat = User::factory()->create(['role' => 'candidat']);

        $this->actingAs($candidat)->post('/mon-profil/cv', [
            'cv' => UploadedFile::fake()->create('cv.exe', 100, 'application/octet-stream'),
        ])->assertSessionHasErrors('cv');

        $this->actingAs($candidat)->post('/mon-profil/cv', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 5121, 'application/pdf'),
        ])->assertSessionHasErrors('cv');

        $this->assertNull($candidat->fresh()->cv_path);

        $this->actingAs($candidat)->post('/mon-profil/cv', [
            'cv' => UploadedFile::fake()->create('cv.docx', 5120, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ])->assertSessionHasNoErrors();

        $this->assertNotNull($candidat->fresh()->cv_path);
    }

    // RG12 : un administrateur ne peut être ni suspendu ni supprimé depuis l'administration
    public function test_rg12_un_administrateur_ne_peut_etre_ni_suspendu_ni_supprime(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $autreAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch("/admin/utilisateurs/{$autreAdmin->id}/toggle")
            ->assertSessionHas('error', 'Impossible de suspendre un admin.');
        $this->actingAs($admin)->delete("/admin/utilisateurs/{$autreAdmin->id}")
            ->assertSessionHas('error', 'Impossible de supprimer un admin.');

        $this->assertDatabaseHas('users', ['id' => $autreAdmin->id, 'suspendu' => false]);
    }

    // RG13 : 5 tentatives par minute au maximum (la connexion est testée dans SecuriteTest)
    public static function formulairesLimites(): array
    {
        return [
            'inscription' => ['/inscription', ['name' => 'Test', 'email' => 'pas-un-email', 'password' => 'x', 'password_confirmation' => 'x', 'role' => 'candidat']],
            'contact' => ['/contact', ['nom' => '', 'email' => 'pas-un-email', 'sujet' => '', 'message' => '']],
            'mot de passe oublié' => ['/mot-de-passe-oublie', ['email' => 'inconnu@example.com']],
        ];
    }

    #[DataProvider('formulairesLimites')]
    public function test_rg13_les_formulaires_sont_limites_a_5_envois_par_minute(string $url, array $donnees): void
    {
        Mail::fake();

        for ($i = 0; $i < 5; $i++) {
            $this->post($url, $donnees)->assertStatus(302);
        }

        $this->post($url, $donnees)->assertStatus(429);
    }
}
