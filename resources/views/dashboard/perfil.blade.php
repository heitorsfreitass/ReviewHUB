@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

    {{-- Header do perfil --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 flex items-center gap-6">
        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
             class="w-20 h-20 rounded-full object-cover">
        <div>
            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
            <p class="text-gray-500">{{ $user->email }}</p>
            <div class="flex gap-4 mt-2 text-sm text-gray-500">
                <span>📦 {{ $user->produtos()->count() }} produtos cadastrados</span>
                <span>⭐ {{ $user->avaliacoes()->count() }} avaliações escritas</span>
            </div>
        </div>
    </div>

    {{-- Editar dados --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="font-bold text-lg mb-5">✏️ Editar dados</h2>
        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4 max-w-md">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                Salvar
            </button>
        </form>
    </div>

    {{-- Minhas avaliações --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="font-bold text-lg mb-5">⭐ Minhas avaliações</h2>
        @forelse($avaliacoes as $av)
            <div class="flex items-start gap-4 py-4 border-b border-gray-50 last:border-0">
                <img src="{{ $av->produto->imagem_url }}" alt="{{ $av->produto->nome }}"
                     class="w-12 h-12 object-cover rounded-lg flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('produtos.show', $av->produto) }}"
                       class="font-medium text-sm hover:text-indigo-600 truncate block">
                        {{ $av->produto->nome }}
                    </a>
                    <div class="flex items-center gap-2 mt-0.5">
                        <x-estrelas :nota="$av->nota" tamanho="sm" />
                        <span class="text-xs text-gray-500">{{ $av->titulo }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $av->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('produtos.avaliacoes.edit', [$av->produto, $av]) }}"
                   class="text-xs text-indigo-600 hover:underline flex-shrink-0">Editar</a>
            </div>
        @empty
            <p class="text-gray-400 text-sm">Você ainda não escreveu nenhuma avaliação.</p>
        @endforelse
        {{ $avaliacoes->links() }}
    </div>

    {{-- Produtos que cadastrei --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="font-bold text-lg mb-5">📦 Produtos que cadastrei</h2>
        @forelse($produtos as $p)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <img src="{{ $p->imagem_url }}" alt="{{ $p->nome }}"
                     class="w-10 h-10 object-cover rounded-lg flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <a href="{{ route('produtos.show', $p) }}"
                       class="font-medium text-sm hover:text-indigo-600 truncate block">{{ $p->nome }}</a>
                    <p class="text-xs text-gray-400">{{ $p->categoria->nome }} · {{ $p->total_avaliacoes }} avaliações</p>
                </div>
                <a href="{{ route('produtos.edit', $p) }}" class="text-xs text-indigo-600 hover:underline">Editar</a>
            </div>
        @empty
            <p class="text-gray-400 text-sm">Você ainda não cadastrou nenhum produto.</p>
        @endforelse
        {{ $produtos->links() }}
    </div>

</div>
@endsection
