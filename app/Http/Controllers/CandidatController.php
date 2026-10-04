<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\GereCompte;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatController extends Controller
{
    use GereCompte;

    // Tableau de bord : compteurs de candidatures et taux de remplissage du profil
    public function dashboard(): View
    {
        $candidat = auth()->user();

        $stats = [
            'envoyees'   => $candidat->candidatures()->count(),
            'acceptees'  => $candidat->candidatures()->where('statut', 'acceptee')->count(),
            'en_attente' => $candidat->candidatures()->where('statut', 'en_attente')->count(),
        ];

        $dernieres_candidatures = $candidat->candidatures()->with('offre')->latest()->take(5)->get();
        $pourcentageProfil = $candidat->pourcentageProfil();

        return view('dashboard.candidat', compact('stats', 'dernieres_candidatures', 'pourcentageProfil'));
    }

    public function profil(): View
    {
        return view('candidat.profil', ['pourcentageProfil' => auth()->user()->pourcentageProfil()]);
    }

    public function profilEdit(): View
    {
        return view('candidat.profil_edit');
    }

    public function profilUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . auth()->id(),
            'telephone'     => 'nullable|string|max:20',
            'ville'         => 'nullable|string|max:100',
            'linkedin'      => 'nullable|string|max:255',
            'portfolio'     => 'nullable|string|max:255',
            'titre_poste'   => 'nullable|string|max:255',
            'disponibilite' => 'nullable|string|max:50',
            'experience'    => 'nullable|string|max:50',
            'a_propos'      => 'nullable|string',
            'competences'   => 'nullable|string',
        ]);

        // only() : le rôle ou le CV ne peuvent pas être modifiés en ajoutant un champ caché au formulaire
        auth()->user()->update($request->only([
            'name', 'email', 'telephone', 'ville', 'linkedin', 'portfolio',
            'titre_poste', 'disponibilite', 'experience', 'a_propos', 'competences'
        ]));

        return redirect()->route('candidat.profil')->with('success', 'Profil mis à jour avec succès.');
    }

    public function cvUpload(Request $request): RedirectResponse
    {
        // RG10 : PDF, DOC ou DOCX, 5 Mo maximum (5120 Ko)
        $request->validate(['cv' => 'required|file|mimes:pdf,doc,docx|max:5120']);

        // On efface l'ancien CV pour ne pas garder de fichiers inutiles sur le serveur
        $this->supprimerFichierCv(auth()->user()->cv_path);

        // Disque privé : le fichier n'est pas accessible par une URL publique
        $path = $request->file('cv')->store('cvs', 'local');
        auth()->user()->update(['cv_path' => $path]);

        return back()->with('success', 'CV uploadé avec succès.');
    }

    public function cvDelete(): RedirectResponse
    {
        if (auth()->user()->cv_path) {
            $this->supprimerFichierCv(auth()->user()->cv_path);
            auth()->user()->update(['cv_path' => null]);
        }
        return back()->with('success', 'CV supprimé.');
    }

    /**
     * Télécharge le CV d'un candidat. Autorisé pour :
     * - le candidat lui-même,
     * - un admin,
     * - un recruteur à qui ce candidat a postulé (RG07).
     * Tout le monde passe par cette méthode : il n'y a pas de lien direct vers le fichier.
     */
    public function cvTelecharger(User $user): StreamedResponse
    {
        $auth = auth()->user();

        $autorise = $auth->id === $user->id
            || $auth->isAdmin()
            || ($auth->isRecruteur() && $user->candidatures()->whereHas('offre', fn($q) => $q->where('user_id', $auth->id))->exists());

        if (!$autorise) {
            abort(403);
        }

        // Les anciens CV sont encore sur le disque public
        foreach (['local', 'public'] as $disk) {
            if ($user->cv_path && Storage::disk($disk)->exists($user->cv_path)) {
                return Storage::disk($disk)->response($user->cv_path);
            }
        }

        abort(404);
    }

    // On essaie les deux disques, car les anciens CV étaient rangés sur le disque public
    private function supprimerFichierCv(?string $path): void
    {
        if ($path) {
            Storage::disk('local')->delete($path);
            Storage::disk('public')->delete($path);
        }
    }

    public function candidatures(): View
    {
        $candidatures = auth()->user()->candidatures()->with('offre')->latest()->paginate(10);
        return view('candidat.candidatures', compact('candidatures'));
    }

    public function parametres(): View
    {
        return view('candidat.parametres');
    }
}
