<?php
namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Contact;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────
    public function dashboard(): View
    {
        $activite_mois = $this->sixDerniersMois()->mapWithKeys(fn($date) => [
            $date->format('M') => [
                'inscriptions' => $this->compterSurMois(User::query(), $date),
                'offres'       => $this->compterSurMois(Offre::query(), $date),
            ]
        ]);

        $stats = [
            'users'             => User::count(),
            'candidats'         => User::where('role', 'candidat')->count(),
            'recruteurs'        => User::where('role', 'recruteur')->count(),
            'offres'            => Offre::where('active', true)->count(),
            'candidatures'      => Candidature::count(),
            'users_mois'        => $this->compterSurMois(User::query(), now()),
            'offres_semaine'    => Offre::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'candidatures_mois' => $this->compterSurMois(Candidature::query(), now()),
            'activite_mois'     => $activite_mois,
        ];

        $derniers_users   = User::latest()->take(5)->get();
        $dernieres_offres = Offre::with('recruteur')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'derniers_users', 'dernieres_offres'));
    }

    // ─── UTILISATEURS ────────────────────────────────────────
    public function users(Request $request): View
    {
        $query = User::latest();
        if ($request->filled('recherche')) {
            $query->where(fn($q) => $q->where('name', 'like', '%'.$request->recherche.'%')
                                      ->orWhere('email', 'like', '%'.$request->recherche.'%'));
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        $users = $query->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function showUser(User $user): View
    {
        $user->load('offres.candidatures', 'candidatures.offre');
        return view('admin.user_show', compact('user'));
    }

    public function toggleUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) return back()->with('error', 'Impossible de suspendre un admin.');
        $user->update(['suspendu' => !$user->suspendu]);
        return back()->with('success', $user->suspendu ? 'Compte suspendu.' : 'Compte réactivé.');
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->isAdmin()) return back()->with('error', 'Impossible de supprimer un admin.');
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    // ─── OFFRES ──────────────────────────────────────────────
    public function offres(Request $request): View
    {
        $query = Offre::with('recruteur')->withCount('candidatures')->latest();
        if ($request->filled('recherche')) {
            $query->where(fn($q) => $q->where('titre', 'like', '%'.$request->recherche.'%')
                                      ->orWhere('entreprise', 'like', '%'.$request->recherche.'%'));
        }
        if ($request->filled('statut')) {
            match($request->statut) {
                'active'   => $query->where('active', true),
                'inactive' => $query->where('active', false),
                default    => null,
            };
        }
        $offres = $query->paginate(15);
        return view('admin.offres', compact('offres'));
    }

    public function toggleOffre(Offre $offre): RedirectResponse
    {
        $offre->update(['active' => !$offre->active]);
        return back()->with('success', 'Statut de l\'offre mis à jour.');
    }

    public function deleteOffre(Offre $offre): RedirectResponse
    {
        $offre->delete();
        return back()->with('success', 'Offre supprimée.');
    }

    // ─── CANDIDATURES ────────────────────────────────────────
    public function candidatures(Request $request): View
    {
        $query = Candidature::with(['candidat', 'offre'])->latest();
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        $candidatures = $query->paginate(15);
        return view('admin.candidatures', compact('candidatures'));
    }

    // ─── CONTACTS ────────────────────────────────────────────
    public function contacts(): View
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts', compact('contacts'));
    }

    public function marquerContactLu(Contact $contact): RedirectResponse
    {
        $contact->update(['lu' => true]);
        return back()->with('success', 'Message marqué comme lu.');
    }

    public function deleteContact(Contact $contact): RedirectResponse
    {
        $contact->delete();
        return back()->with('success', 'Message supprimé.');
    }

    // ─── STATISTIQUES ────────────────────────────────────────
    public function stats(): View
    {
        $mois = $this->sixDerniersMois();

        $inscriptions_mois = $mois->mapWithKeys(fn($date) => [
            $date->format('M') => $this->compterSurMois(User::query(), $date)
        ]);

        $offres_mois = $mois->mapWithKeys(fn($date) => [
            $date->format('M') => $this->compterSurMois(Offre::query(), $date)
        ]);

        $total_candidatures = Candidature::count();
        $acceptees = Candidature::where('statut', 'acceptee')->count();

        $stats = [
            'inscriptions_mois'   => $inscriptions_mois,
            'offres_mois'         => $offres_mois,
            'total_users'         => User::count(),
            'total_offres'        => Offre::count(),
            'total_candidatures'  => $total_candidatures,
            'taux_acceptation'    => $total_candidatures > 0 ? round(($acceptees / $total_candidatures) * 100) : 0,
        ];

        return view('admin.stats', compact('stats'));
    }

    // ─── PARAMÈTRES ──────────────────────────────────────────
    public function parametres(): View
    {
        return view('admin.parametres');
    }

    public function updateParametres(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);
        auth()->user()->update($request->only('name', 'email'));
        return back()->with('success', 'Informations mises à jour.');
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

    // ─── OUTILS ──────────────────────────────────────────────
    private function sixDerniersMois()
    {
        return collect(range(5, 0))->map(fn($i) => now()->startOfMonth()->subMonths($i));
    }

    // Filtre sur le mois ET l'année (sinon mars 2025 et mars 2026 seraient additionnés)
    private function compterSurMois($query, $date): int
    {
        return $query->whereYear('created_at', $date->year)
                     ->whereMonth('created_at', $date->month)
                     ->count();
    }
}
