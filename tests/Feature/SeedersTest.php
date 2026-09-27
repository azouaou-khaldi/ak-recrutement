<?php

namespace Tests\Feature;

use App\Models\Candidature;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_le_seeder_de_demo_remplit_la_base(): void
    {
        $this->seed(DemoSeeder::class);

        $this->assertSame(4, User::where('role', 'recruteur')->count());
        $this->assertSame(8, User::where('role', 'candidat')->count());
        $this->assertSame(10, Offre::count());
        $this->assertSame(9, Offre::where('active', true)->count());
        $this->assertSame(13, Candidature::count());
        $this->assertSame(3, Contact::count());

        // Les comptes de démo sont utilisables avec le mot de passe documenté
        $lea = User::where('email', 'lea.dubois@example.com')->firstOrFail();
        $this->assertTrue(Hash::check(DemoSeeder::MOT_DE_PASSE, $lea->password));
    }

    public function test_le_seeder_de_demo_peut_etre_relance_sans_doublon(): void
    {
        $this->seed(DemoSeeder::class);
        $this->seed(DemoSeeder::class);

        $this->assertSame(12, User::count());
        $this->assertSame(10, Offre::count());
        $this->assertSame(13, Candidature::count());
    }

    public function test_le_seeder_de_demo_refuse_la_production(): void
    {
        $this->app['env'] = 'production';

        // Appel direct : la commande db:seed demanderait d'abord une confirmation en production
        $this->expectException(RuntimeException::class);
        $this->app->make(DemoSeeder::class)->run();
    }

    public function test_le_seeder_admin_utilise_le_env_et_ne_modifie_pas_un_compte_existant(): void
    {
        config(['app.admin_email' => 'admin@example.com', 'app.admin_password' => 'MotDePasse123!']);

        $this->seed(AdminSeeder::class);
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue($admin->isAdmin());
        $this->assertTrue(Hash::check('MotDePasse123!', $admin->password));

        // Relancé avec un autre mot de passe : le compte existant n'est pas écrasé
        config(['app.admin_password' => 'AutreMotDePasse456!']);
        $this->seed(AdminSeeder::class);
        $this->assertTrue(Hash::check('MotDePasse123!', $admin->fresh()->password));
    }
}
