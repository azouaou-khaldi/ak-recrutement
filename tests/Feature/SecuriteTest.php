<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecuriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_connexion_est_bloquee_apres_5_tentatives(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/connexion', ['email' => $user->email, 'password' => 'mauvais']);
        }

        $this->post('/connexion', ['email' => $user->email, 'password' => 'mauvais'])
            ->assertStatus(429);
    }

    public function test_une_offre_desactivee_est_invisible_pour_le_public(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id, 'active' => false]);

        $this->get("/offres/{$offre->id}")->assertStatus(404);
        $this->actingAs($recruteur)->get("/offres/{$offre->id}")->assertStatus(200);
    }

    public function test_on_ne_peut_pas_postuler_a_une_offre_desactivee(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $candidat = User::factory()->create(['role' => 'candidat']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id, 'active' => false]);

        $this->actingAs($candidat)->post("/offres/{$offre->id}/postuler")->assertStatus(404);
        $this->assertDatabaseCount('candidatures', 0);
    }

    public function test_le_cv_est_protege(): void
    {
        Storage::fake('local');

        $candidat = User::factory()->create(['role' => 'candidat']);
        $this->actingAs($candidat)->post('/mon-profil/cv', [
            'cv' => UploadedFile::fake()->create('cv.pdf', 100, 'application/pdf'),
        ]);
        $candidat->refresh();

        // Le candidat voit son propre CV
        $this->get("/cv/{$candidat->id}")->assertStatus(200);

        // Un recruteur sans lien avec le candidat ne peut pas le voir
        $autreRecruteur = User::factory()->create(['role' => 'recruteur']);
        $this->actingAs($autreRecruteur)->get("/cv/{$candidat->id}")->assertStatus(403);

        // Un recruteur à qui le candidat a postulé peut le voir
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id]);
        Candidature::create(['offre_id' => $offre->id, 'user_id' => $candidat->id]);
        $this->actingAs($recruteur)->get("/cv/{$candidat->id}")->assertStatus(200);
    }

    public function test_un_visiteur_ne_peut_pas_telecharger_un_cv(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat']);

        $this->get("/cv/{$candidat->id}")->assertRedirect('/connexion');
    }

    public function test_le_changement_de_mot_de_passe_exige_un_mot_de_passe_fort(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat', 'password' => 'Ancien123!']);

        $this->actingAs($candidat)->put('/parametres/password', [
            'current_password'      => 'Ancien123!',
            'password'              => '12345678',
            'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('password');
    }

    public function test_un_candidat_ne_peut_pas_acceder_a_l_admin(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat']);

        $this->actingAs($candidat)->get('/admin')->assertStatus(403);
    }
}
