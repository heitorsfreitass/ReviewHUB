@extends('layouts.app')
@section('title', 'Editar avaliação')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="flex items-center gap-4 mb-8 bg-white rounded-2xl border border-gray-100 p-4">
        <img src="{{ $produto->imagem_url }}" alt="{{ $produto->nome }}"
            class="w-16 h-16 object-cover rounded-xl">
        <div>
            <h1 class="font-bold text-lg">Editar avaliação</h1>
            <p class="text-sm text-gray-500">{{ $produto->nome }}</p>
        </div>
    </div>

    <form action="{{ route('produtos.avaliacoes.update', [$produto, $avaliacao]) }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div x-data="{ nota: {{ old('nota', $avaliacao->nota) }}, hover: 0 }">
            <label class="block text-sm font-medium text-gray-700 mb-3">Nota *</label>
            <input type="hidden" name="nota" :value="nota">
            <div class="flex gap-1">
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" @click="nota = {{ $i }}"
                    @mouseenter="hover = {{ $i }}" @mouseleave="hover = 0"
                    class="text-4xl transition-transform hover:scale-110">
                    <span :class="(hover || nota) >= {{ $i }} ? 'star-filled' : 'star-empty'">★</span>
                    </button>
                    @endfor
            </div>
            @error('nota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
            <input type="text" name="titulo" value="{{ old('titulo', $avaliacao->titulo) }}"
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                          {{ $errors->has('titulo') ? 'border-red-400' : 'border-gray-200' }}">
            @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Avaliação *</label>
            <textarea name="conteudo" rows="5"
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none
                             {{ $errors->has('conteudo') ? 'border-red-400' : 'border-gray-200' }}">{{ old('conteudo', $avaliacao->conteudo) }}</textarea>
            @error('conteudo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border border-gray-100 rounded-xl p-5 space-y-4 bg-gray-50">
            <p class="text-sm font-medium text-gray-700">🛒 Detalhes da compra</p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Preço pago (R$)</label>
                    <input type="number" name="preco_pago" step="0.01" min="0"
                        value="{{ old('preco_pago', $avaliacao->preco_pago) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Loja</label>
                    <input type="text" name="loja" value="{{ old('loja', $avaliacao->loja) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Link da loja</label>
                <input type="url" name="url_loja" value="{{ old('url_loja', $avaliacao->url_loja) }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-300">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Recomenda? *</label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recomenda" value="1"
                        {{ old('recomenda', $avaliacao->recomenda ? '1' : '0') === '1' ? 'checked' : '' }}>
                    <span class="text-sm">👍 Sim</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recomenda" value="0"
                        {{ old('recomenda', $avaliacao->recomenda ? '1' : '0') === '0' ? 'checked' : '' }}>
                    <span class="text-sm">👎 Não</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Fotos da avaliação
            </label>

            {{-- Imagens atuais --}}
            @if($avaliacao->imagens_urls)
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($avaliacao->imagens_urls as $url)
                <img src="{{ $url }}" class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mb-2">Selecione novas imagens para substituir as atuais.</p>
            @endif

            <input type="file" name="imagens[]" accept="image/*" multiple
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full
                  file:border-0 file:text-sm file:font-medium
                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                onchange="previewImagens(this)">

            <div id="preview-imagens" class="flex flex-wrap gap-2 mt-3"></div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition">
                Salvar alterações
            </button>
            <a href="{{ route('produtos.show', $produto) }}"
                class="flex-1 text-center border border-gray-200 text-gray-600 hover:bg-gray-50 py-3 rounded-xl text-sm transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection