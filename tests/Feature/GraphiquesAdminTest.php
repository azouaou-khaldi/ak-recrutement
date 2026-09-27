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

        $html = $this->actingAs($admin)->get('/admin')->assertOk()->getContent();

        // Hauteur de chaque barre = part du mois le plus actif (entre 0 et 1)
        preg_match_all('/calc\(\(100% - 16px\) \* ([\d.]+)\)/', $html, $parts);

        $this->assertNotEmpty($parts[1], 'Aucune barre trouvée');
        $this->assertEquals(1, max(array_map('floatval', $parts[1])), 'Le mois le plus actif doit remplir le cadre');
        $this->assertLessThanOrEqual(1, max(array_map('floatval', $parts[1])));
    }

    public function test_les_valeurs_sont_affichees_au_dessus_des_barres(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(7)->create(['role' => 'candidat']);

        $html = $this->actingAs($admin)->get('/admin')->assertOk()->getContent();

        // 6 mois × 2 séries = 12 valeurs visibles, dont les 8 inscriptions du mois en cours
        $this->assertSame(12, substr_count($html, 'data-valeur-barre'));
        $this->assertMatchesRegularExpression('/Inscriptions en ' . preg_quote(ucfirst(now()->translatedFormat('M')), '/') . ' : <\/span>8\s*</', $html);
    }
}
