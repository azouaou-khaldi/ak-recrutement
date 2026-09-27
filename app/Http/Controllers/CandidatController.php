<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatController extends Controller
{
    public function profil(): View
    {
        return view('candidat.profil');
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

        auth()->user()->update($request->only([
            'name', 'email', 'telephone', 'ville', 'linkedin', 'portfolio',
            'titre_poste', 'disponibilite', 'experience', 'a_propos', 'competences'
        ]));

        return redirect()->route('candidat.profil')->with('success', 'Profil mis à jour avec succès.');
    }

    public function cvUpload(Request $request): RedirectResponse
    {
        $request->validate(['cv' => 'required|file|mimes:pdf,doc,docx|max:5120']);

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
     * - un recruteur à qui ce candidat a postulé.
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

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Mot de passe mis à jour.');
    }

    public function deleteCompte(): RedirectResponse
    {
        $user = auth()->user();
        auth()->logout();
        $user->delete();
        return redirect()->route('home')->with('success', 'Votre compte a été supprimé.');
    }
}
