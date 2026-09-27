<?php

namespace Tests\Feature;

use App\Mail\ReponseContactMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessagesContactTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Contact $contact;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->contact = Contact::create([
            'nom'     => 'Jean Visiteur',
            'email'   => 'jean.visiteur@exemple.fr',
            'sujet'   => 'Inscription des entreprises',
            'message' => 'Bonjour, comment inscrire mon entreprise sur la plateforme ?',
        ]);
    }

    public function test_la_liste_affiche_le_nom_et_le_sujet_sans_le_contenu(): void
    {
        $this->actingAs($this->admin)->get('/admin/contacts')
            ->assertOk()
            ->assertSee('Jean Visiteur')
            ->assertSee('Inscription des entreprises')
            ->assertDontSee('comment inscrire mon entreprise')
            ->assertSee(route('admin.contacts.show', $this->contact), false);
    }

    public function test_le_message_passe_en_lu_a_la_consultation(): void
    {
        $this->assertFalse($this->contact->fresh()->lu);

        $this->actingAs($this->admin)->get("/admin/contacts/{$this->contact->id}")
            ->assertOk()
            ->assertSee('comment inscrire mon entreprise');

        $this->assertTrue($this->contact->fresh()->lu);
    }

    public function test_un_email_est_envoye_quand_l_admin_repond(): void
    {
        Mail::fake();

        $this->actingAs($this->admin)
            ->post("/admin/contacts/{$this->contact->id}/repondre", ['reponse' => 'Bonjour Jean, il suffit de créer un compte recruteur.'])
            ->assertRedirect("/admin/contacts/{$this->contact->id}")
            ->assertSessionHas('success');

        // ShouldQueue : l'e-mail est mis en file d'attente, à l'adresse saisie dans le formulaire de contact
        Mail::assertQueued(ReponseContactMail::class, function (ReponseContactMail $mail) {
            return $mail->hasTo('jean.visiteur@exemple.fr')
                && $mail->reponse === 'Bonjour Jean, il suffit de créer un compte recruteur.'
                && $mail->contact->is($this->contact);
        });

        $this->assertSame('Bonjour Jean, il suffit de créer un compte recruteur.', $this->contact->fresh()->reponse);
        $this->assertNotNull($this->contact->fresh()->repondu_le);
    }

    public function test_une_reponse_vide_est_refusee_et_aucun_email_n_est_envoye(): void
    {
        Mail::fake();

        $this->actingAs($this->admin)
            ->post("/admin/contacts/{$this->contact->id}/repondre", ['reponse' => ''])
            ->assertSessionHasErrors('reponse');

        Mail::assertNothingQueued();
    }

    public function test_seul_l_admin_peut_lire_et_repondre(): void
    {
        Mail::fake();
        $recruteur = User::factory()->create(['role' => 'recruteur']);

        $this->actingAs($recruteur)->get("/admin/contacts/{$this->contact->id}")->assertForbidden();
        $this->actingAs($recruteur)->post("/admin/contacts/{$this->contact->id}/repondre", ['reponse' => 'Test'])->assertForbidden();

        $this->assertFalse($this->contact->fresh()->lu);
        Mail::assertNothingQueued();
    }

    public function test_l_email_de_reponse_contient_la_reponse_echappee(): void
    {
        $mail = new ReponseContactMail($this->contact, "Bonjour <script>alert(1)</script>\nÀ bientôt");

        $mail->assertSeeInHtml('Bonjour Jean Visiteur', false);
        $mail->assertSeeInHtml('&lt;script&gt;', false);
        $mail->assertDontSeeInHtml('<script>alert(1)</script>', false);
        $mail->assertHasSubject('Re : Inscription des entreprises');
    }
}
