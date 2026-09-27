<?php

namespace Tests\Feature;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccueilTest extends TestCase
{
    use RefreshDatabase;

    public function test_l_accueil_affiche_les_vrais_chiffres_de_la_base(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        User::factory()->count(3)->create(['role' => 'candidat']);

        Offre::factory()->create(['user_id' => $recruteur->id, 'entreprise' => 'TechCorp', 'active' => true]);
        Offre::factory()->create(['user_id' => $recruteur->id, 'entreprise' => 'TechCorp', 'active' => true]);
        Offre::factory()->create(['user_id' => $recruteur->id, 'entreprise' => 'BioLab', 'active' => true]);
        // Une offre désactivée ne doit pas être comptée
        Offre::factory()->create(['user_id' => $recruteur->id, 'entreprise' => 'Fantome', 'active' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSeeInOrder(['3', 'Offres actives', '2', 'Entreprises', '3', 'Candidats inscrits']);
    }

    public function test_l_accueil_accorde_au_singulier(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Offre active')
            ->assertSee('Entreprise')
            ->assertSee('Candidat inscrit');
    }
}
