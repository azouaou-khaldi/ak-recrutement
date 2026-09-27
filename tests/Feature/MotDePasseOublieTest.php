<?php

namespace Tests\Feature;

use App\Mail\ReinitialisationMotDePasseMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class MotDePasseOublieTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_page_de_connexion_propose_le_lien_mot_de_passe_oublie(): void
    {
        $this->get('/connexion')->assertOk()->assertSee(route('password.request'), false);
        $this->get('/mot-de-passe-oublie')->assertOk()->assertSee('Envoyer le lien');
    }

    public function test_un_email_de_reinitialisation_est_envoye(): void
    {
        Mail::fake();
        $user = User::factory()->create(['email' => 'lea@example.com']);

        $this->post('/mot-de-passe-oublie', ['email' => 'lea@example.com'])
            ->assertSessionHas('status');

        Mail::assertQueued(ReinitialisationMotDePasseMail::class, fn ($mail) => $mail->hasTo('lea@example.com') && $mail->user->is($user));
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'lea@example.com']);
    }

    public function test_le_meme_message_s_affiche_pour_une_adresse_inconnue(): void
    {
        Mail::fake();
        User::factory()->create(['email' => 'lea@example.com']);

        $existant = $this->post('/mot-de-passe-oublie', ['email' => 'lea@example.com'])->getSession()->get('status');
        $inconnu = $this->post('/mot-de-passe-oublie', ['email' => 'inconnu@example.com'])->getSession()->get('status');

        // On ne révèle pas si un compte existe (protection contre l'énumération des comptes)
        $this->assertSame($existant, $inconnu);
        Mail::assertQueued(ReinitialisationMotDePasseMail::class, 1);
    }

    public function test_l_email_contient_un_lien_vers_la_page_de_reinitialisation(): void
    {
        $user = User::factory()->create(['email' => 'lea@example.com']);

        $mail = new ReinitialisationMotDePasseMail($user, 'jeton-de-test');

        $mail->assertSeeInHtml(route('password.reset', ['token' => 'jeton-de-test', 'email' => 'lea@example.com']), false);
        $mail->assertHasSubject('Réinitialisation de votre mot de passe - AK Recrutement');
    }

    public function test_le_mot_de_passe_est_reinitialise_avec_un_jeton_valide(): void
    {
        $user = User::factory()->create(['email' => 'lea@example.com']);
        $jeton = Password::createToken($user);

        $this->get("/reinitialiser-mot-de-passe/{$jeton}?email=lea@example.com")
            ->assertOk()
            ->assertSee('lea@example.com');

        $this->post('/reinitialiser-mot-de-passe', [
            'token' => $jeton, 'email' => 'lea@example.com',
            'password' => 'Nouveau123!', 'password_confirmation' => 'Nouveau123!',
        ])->assertRedirect('/connexion')->assertSessionHas('status');

        $this->assertTrue(Hash::check('Nouveau123!', $user->fresh()->password));

        // Le lien ne peut servir qu'une fois
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'lea@example.com']);
    }

    public function test_un_jeton_invalide_est_refuse(): void
    {
        $user = User::factory()->create(['email' => 'lea@example.com']);
        $ancien = $user->password;

        $this->post('/reinitialiser-mot-de-passe', [
            'token' => 'faux-jeton', 'email' => 'lea@example.com',
            'password' => 'Nouveau123!', 'password_confirmation' => 'Nouveau123!',
        ])->assertSessionHasErrors('email');

        $this->assertSame($ancien, $user->fresh()->password);
    }

    public function test_le_nouveau_mot_de_passe_doit_etre_fort(): void
    {
        $user = User::factory()->create(['email' => 'lea@example.com']);
        $jeton = Password::createToken($user);

        $this->post('/reinitialiser-mot-de-passe', [
            'token' => $jeton, 'email' => 'lea@example.com',
            'password' => '12345678', 'password_confirmation' => '12345678',
        ])->assertSessionHasErrors('password');
    }
}
