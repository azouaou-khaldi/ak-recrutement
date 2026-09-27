<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthentificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_accueil_saffiche(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_un_candidat_peut_sinscrire(): void
    {
        $response = $this->post('/inscription', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'MotDePasse123!',
            'password_confirmation' => 'MotDePasse123!',
            'role' => 'candidat',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'jean@example.com',
            'role' => 'candidat',
        ]);
        $response->assertRedirect('/tableau-de-bord');
    }

    public function test_un_utilisateur_peut_se_connecter(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('MotDePasse123!'),
        ]);

        $response = $this->post('/connexion', [
            'email' => $user->email,
            'password' => 'MotDePasse123!',
        ]);

        $this->assertAuthenticatedAs($user);
    }

    public function test_un_compte_suspendu_est_deconnecte(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('MotDePasse123!'),
            'suspendu' => true,
        ]);

        $this->post('/connexion', [
            'email' => $user->email,
            'password' => 'MotDePasse123!',
        ]);

        $response = $this->get('/tableau-de-bord');
        $response->assertRedirect('/connexion');
    }
}
