<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraphiquesAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_les_barres_restent_dans_le_cadre_meme_avec_beaucoup_d_inscriptions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(60)->create(['role' => 'candidat']);

        foreach (['/admin', '/admin/statistiques'] as $page) {
            $html = $this->actingAs($admin)->get($page)->assertOk()->getContent();

            preg_match_all('/height: max\(4px, (\d+)%\)/', $html, $hauteurs);

            $this->assertNotEmpty($hauteurs[1], "Aucune barre trouvée sur $page");
            // Le mois le plus actif fait exactement 100 %, aucune barre ne dépasse
            $this->assertSame(100, max(array_map('intval', $hauteurs[1])), $page);
        }
    }
}
