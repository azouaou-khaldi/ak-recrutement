<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_favicon_est_un_vrai_fichier_ico_multi_tailles(): void
    {
        $ico = file_get_contents(public_path('favicon.ico'));

        // L'ancien favicon.ico de Laravel était vide (0 octet)
        $this->assertGreaterThan(0, strlen($ico));
        // En-tête ICO : réservé = 0, type = 1 (icône), puis le nombre d'images
        $entete = unpack('vreserve/vtype/vnombre', substr($ico, 0, 6));
        $this->assertSame(['reserve' => 0, 'type' => 1, 'nombre' => 3], $entete);

        $this->assertFileExists(public_path('favicon.svg'));
        $this->assertFileExists(public_path('apple-touch-icon.png'));
    }

    public function test_toutes_les_mises_en_page_declarent_les_icones_et_affichent_le_logo(): void
    {
        $candidat = User::factory()->create(['role' => 'candidat']);
        $recruteur = User::factory()->create(['role' => 'recruteur']);
        $admin = User::factory()->create(['role' => 'admin']);

        $pages = [[null, '/'], [$candidat, '/tableau-de-bord'], [$recruteur, '/tableau-de-bord'], [$admin, '/admin']];

        foreach ($pages as [$user, $url]) {
            $html = ($user ? $this->actingAs($user) : $this)->get($url)->assertOk()->getContent();

            // Icônes versionnées : le navigateur ne réutilise pas l'icône Laravel gardée en cache
            $this->assertMatchesRegularExpression('/<link rel="icon" href="[^"]*favicon\.ico\?v=\d+"/', $html, $url);
            $this->assertMatchesRegularExpression('/<link rel="icon" href="[^"]*favicon\.svg\?v=\d+" type="image\/svg\+xml"/', $html, $url);
            $this->assertStringContainsString('rel="apple-touch-icon"', $html, $url);

            // Logo dans la barre de navigation, juste avant le nom du site
            $this->assertMatchesRegularExpression('/<svg[^>]*viewBox="0 0 64 64"[^>]*aria-hidden="true".*?AK.*?Recrutement/s', $html, $url);
        }
    }
}
