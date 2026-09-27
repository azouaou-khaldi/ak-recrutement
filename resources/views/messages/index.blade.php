@extends(auth()->check() && auth()->user()->isCandidat() ? 'layouts.candidat' : (auth()->check() && auth()->user()->isRecruteur() ? 'layouts.recruteur' : 'layouts.app'))
@section('title', "Messagerie - AK Recrutement")

@section('content')
<section class="px-6 py-16 min-h-screen">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-extrabold mb-8">Mes <span class="text-brand">messages</span></h1>
        @if ($conversations->isEmpty())
            <div class="bg-white border border-lightBorder rounded-2xl p-10 text-center text-gray-600">Aucune conversation pour le moment.</div>
        @else
            <div class="space-y-3">
                @foreach ($conversations as $otherUserId => $messages)
                    @php $otherUser = $messages->first()->sender_id == auth()->id() ? $messages->first()->receiver : $messages->first()->sender; @endphp
                    <a href="{{ route('messages.show', $otherUser) }}" class="block bg-white border border-lightBorder rounded-xl p-4 hover:border-brand transition">
                        <p class="font-bold">{{ $otherUser->name }}</p>
                        <p class="text-gray-600 text-sm truncate">{{ $messages->first()->contenu }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
