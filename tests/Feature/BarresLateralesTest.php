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
}
