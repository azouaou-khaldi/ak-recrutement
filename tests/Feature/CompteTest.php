<?php

namespace Tests\Feature;

use App\Mail\ContactAdminMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Actions de compte partagées via le trait GereCompte, et configuration de l'adresse admin.
 */
class CompteTest extends TestCase
{
    use RefreshDatabase;

    public static function routesMotDePasse(): array
    {
        return [
            'candidat'  => ['candidat', '/parametres/password'],
            'recruteur' => ['recruteur', '/recruteur/parametres/password'],
            'admin'     => ['admin', '/admin/parametres/password'],
        ];
    }

    #[DataProvider('routesMotDePasse')]
    public function test_chaque_role_peut_changer_son_mot_de_passe(string $role, string $url): void
    {
        $user = User::factory()->create(['role' => $role, 'password' => 'Ancien123!']);

        $this->actingAs($user)->put($url, [
            'current_password' => 'Ancien123!',
            'password' => 'Nouveau123!', 'password_confirmation' => 'Nouveau123!',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('Nouveau123!', $user->fresh()->password));
    }

    #[DataProvider('routesMotDePasse')]
    public function test_le_mot_de_passe_actuel_est_verifie(string $role, string $url): void
    {
        $user = User::factory()->create(['role' => $role, 'password' => 'Ancien123!']);

        $this->actingAs($user)->put($url, [
            'current_password' => 'Mauvais123!',
            'password' => 'Nouveau123!', 'password_confirmation' => 'Nouveau123!',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('Ancien123!', $user->fresh()->password));
    }

    public static function routesSuppression(): array
    {
        return [
            'candidat'  => ['candidat', '/mon-compte'],
            'recruteur' => ['recruteur', '/recruteur/compte'],
        ];
    }

    #[DataProvider('routesSuppression')]
    public function test_un_utilisateur_peut_supprimer_son_compte(string $role, string $url): void
    {
        $user = User::factory()->create(['role' => $role]);

        $this->actingAs($user)->delete($url)->assertRedirect('/');

        $this->assertModelMissing($user);
        $this->assertGuest();
    }

    public function test_le_formulaire_de_contact_ecrit_a_l_adresse_admin_du_env(): void
    {
        Mail::fake();
        config(['app.admin_email' => 'responsable@example.com']);

        $this->post('/contact', ['nom' => 'Jean', 'email' => 'jean@example.com', 'sujet' => 'Question', 'message' => 'Bonjour'])
            ->assertSessionHas('success');

        Mail::assertQueued(ContactAdminMail::class, fn ($mail) => $mail->hasTo('responsable@example.com'));
    }
}
