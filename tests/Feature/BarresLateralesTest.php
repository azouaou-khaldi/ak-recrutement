<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BarresLateralesTest extends TestCase
{
    use RefreshDatabase;

    public static function espaces(): array
    {
        return [
            'admin'     => ['admin', '/admin', ['📊', '👥', '💼', '📄', '✉️', '📈', '⚙️', '🚪']],
            'recruteur' => ['recruteur', '/tableau-de-bord', ['📊', '💼', '👥', '💬', '👤', '⚙️', '🚪']],
            'candidat'  => ['candidat', '/tableau-de-bord', []],
        ];
    }

    #[DataProvider('espaces')]
    public function test_la_barre_laterale_utilise_les_icones_svg(string $role, string $url, array $anciensEmojis): void
    {
        $user = User::factory()->create(['role' => $role]);

        $reponse = $this->actingAs($user)->get($url)->assertOk();

        $html = $reponse->getContent();
        $barre = substr($html, strpos($html, '<aside'), strpos($html, '</aside>') - strpos($html, '<aside'));

        $this->assertStringContainsString('<svg', $barre);
        foreach ($anciensEmojis as $emoji) {
            $this->assertStringNotContainsString($emoji, $barre);
        }
    }

    public function test_les_initiales_accentuees_s_affichent_correctement(): void
    {
        // substr() coupait le « é » (2 octets en UTF-8) et affichait « L� » : on utilise mb_substr()
        $lea = User::factory()->create(['role' => 'candidat', 'name' => 'Léa Dubois']);

        $html = $this->actingAs($lea)->get('/tableau-de-bord')->assertOk()->getContent();

        $this->assertStringContainsString('LÉ', $html);
        $this->assertTrue(mb_check_encoding($html, 'UTF-8'), 'La page contient un caractère UTF-8 coupé');
    }
}
