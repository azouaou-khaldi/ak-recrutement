@extends(auth()->check() && auth()->user()->isCandidat() ? 'layouts.candidat' : (auth()->check() && auth()->user()->isRecruteur() ? 'layouts.recruteur' : 'layouts.app'))
@section('title', "Conversation - AK Recrutement")

@section('content')
<section class="px-6 py-16">
    <div class="max-w-2xl mx-auto bg-white border border-lightBorder rounded-2xl p-6 flex flex-col" style="height:70vh">
        <h1 class="text-xl font-extrabold mb-4">{{ $user->name }}</h1>
        <div class="flex-1 overflow-y-auto space-y-3 mb-4">
            @foreach ($messages as $message)
                <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs px-4 py-2 rounded-xl {{ $message->sender_id === auth()->id() ? 'bg-brand text-white' : 'bg-gray-100 text-gray-800 border border-lightBorder' }}">
                        {{ $message->contenu }}
                    </div>
                </div>
            @endforeach
        </div>
        <form method="POST" action="{{ route('messages.store', $user) }}" class="flex gap-2">
            @csrf
            <input type="text" name="contenu" required placeholder="Votre message..."
                class="flex-1 bg-white border border-lightBorder text-gray-900 rounded-lg px-4 py-2 focus:outline-none focus:border-brand placeholder-gray-400">
            <button class="bg-brand hover:bg-brandDark text-white px-5 py-2 rounded-lg font-semibold transition">Envoyer</button>
        </form>
    </div>
</section>
@endsection
