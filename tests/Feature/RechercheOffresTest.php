<?php

namespace Tests\Feature;

use App\Models\Offre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RechercheOffresTest extends TestCase
{
    use RefreshDatabase;

    private function creerOffres(): void
    {
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        Offre::factory()->create(['user_id' => $recruteur->id, 'titre' => 'Développeur Laravel']);
        Offre::factory()->create(['user_id' => $recruteur->id, 'titre' => 'Comptable']);
    }

    public function test_la_page_complete_contient_le_formulaire_et_les_resultats(): void
    {
        $this->creerOffres();

        $this->get('/offres?recherche=Laravel')
            ->assertOk()
            ->assertSee('data-recherche-offres', false)
            ->assertSee('Développeur Laravel')
            ->assertDontSee('Comptable');
    }

    public function test_la_recherche_en_direct_renvoie_uniquement_la_liste(): void
    {
        $this->creerOffres();

        $this->get('/offres?recherche=Laravel', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertSee('Développeur Laravel')
            ->assertDontSee('Comptable')
            ->assertDontSee('<html', false)
            ->assertDontSee('data-recherche-offres', false);
    }

    public function test_un_message_indique_quand_aucune_offre_ne_correspond(): void
    {
        $this->creerOffres();

        $this->get('/offres?recherche=Astronaute', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertSee('Aucune offre ne correspond');
    }

    public function test_la_recherche_protege_contre_le_xss(): void
    {
        $this->get('/offres?recherche=<script>alert(1)</script>', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_le_menu_mobile_est_present(): void
    {
        $this->get('/offres')
            ->assertOk()
            ->assertSee('aria-controls="menu-mobile"', false)
            ->assertSee('id="menu-mobile"', false);
    }
}
