<?php

namespace Tests\Feature;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OffreEtCandidatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_recruteur_peut_publier_une_offre(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);

        $response = $this->actingAs($recruteur)->post('/offres', [
            'titre' => 'Développeur PHP',
            'entreprise' => 'TechCorp',
            'lieu' => 'Paris',
            'type_contrat' => 'CDI',
            'description' => 'Une belle offre.',
        ]);

        $this->assertDatabaseHas('offres', ['titre' => 'Développeur PHP']);
        $response->assertRedirect();
    }

    public function test_un_candidat_ne_peut_pas_publier_une_offre(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat']);

        $response = $this->actingAs($candidat)->get('/offres/creer');

        $response->assertStatus(403);
    }

    public function test_un_candidat_peut_postuler_a_une_offre(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $candidat = User::factory()->create(['role' => 'candidat']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id]);

        $response = $this->actingAs($candidat)->post("/offres/{$offre->id}/postuler", [
            'message' => 'Je suis très motivé.',
        ]);

        $this->assertDatabaseHas('candidatures', [
            'offre_id' => $offre->id,
            'user_id' => $candidat->id,
        ]);
    }

    public function test_un_recruteur_ne_peut_pas_postuler(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $offre = Offre::factory()->create(['user_id' => $recruteur->id]);

        $response = $this->actingAs($recruteur)->post("/offres/{$offre->id}/postuler", [
            'message' => 'Test',
        ]);

        $response->assertStatus(403);
    }
}
