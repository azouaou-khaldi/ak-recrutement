<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();

        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy(function ($message) use ($userId) {
                return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user): View
    {
        $authId = auth()->id();

        $this->authorizeConversation($user);

        $messages = Message::where(function ($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->orderBy('created_at')
            ->get();

        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->update(['lu' => true]);

        return view('messages.show', compact('messages', 'user'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $this->authorizeConversation($user);

        $request->validate(['contenu' => 'required|string']);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'offre_id' => $request->offre_id,
            'contenu' => $request->contenu,
        ]);

        return back();
    }

    /**
     * Vérifie que l'utilisateur connecté a le droit de discuter avec $user :
     * - un échange existe déjà entre les deux, OU
     * - l'un est candidat ayant postulé à une offre de l'autre (recruteur), OU
     * - l'un est admin.
     */
    private function authorizeConversation(User $user): void
    {
        $auth = auth()->user();

        if ($auth->id === $user->id) {
            abort(403);
        }

        if ($auth->isAdmin()) {
            return;
        }

        $dejaEchange = Message::where(function ($q) use ($auth, $user) {
                $q->where('sender_id', $auth->id)->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($auth, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $auth->id);
            })->exists();

        if ($dejaEchange) {
            return;
        }

        if ($auth->isCandidat() && $user->isRecruteur()) {
            $lien = $auth->candidatures()->whereHas('offre', fn ($q) => $q->where('user_id', $user->id))->exists();
            if ($lien) {
                return;
            }
        }

        if ($auth->isRecruteur() && $user->isCandidat()) {
            $lien = $user->candidatures()->whereHas('offre', fn ($q) => $q->where('user_id', $auth->id))->exists();
            if ($lien) {
                return;
            }
        }

        abort(403, 'Vous ne pouvez pas contacter cet utilisateur.');
    }
}
