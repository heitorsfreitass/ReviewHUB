@extends('layouts.app')
@section('title', 'Avaliar: ' . $produto->nome)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Header com info do produto --}}
    <div class="flex items-center gap-4 mb-8 bg-white rounded-2xl border border-gray-100 p-4">
        <img src="{{ $produto->imagem_url }}" alt="{{ $produto->nome }}"
            class="w-16 h-16 object-cover rounded-xl"
            onerror="this.src='https://placehold.co/100x100/f3f4f6/9ca3af?text=?'">
        <div>
            <p class="text-xs text-indigo-600 font-medium">{{ $produto->categoria->nome }}</p>
            <h1 class="font-bold text-lg">{{ $produto->nome }}</h1>
            @if($produto->marca)
            <p class="text-sm text-gray-500">{{ $produto->marca }}</p>
            @endif
        </div>
    </div>

    <form action="{{ route('produtos.avaliacoes.store', $produto) }}" method="POST" enctype="multipart/form-data"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf

        {{-- NOTA COM ESTRELAS INTERATIVAS --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Sua nota <span class="text-red-500">*</span>
            </label>

            {{--
                Estrelas interativas com Alpine.js + CSS puro.
                O campo hidden "nota" é enviado no formulário.
            --}}
            <div x-data="{ nota: {{ old('nota', 0) }}, hover: 0 }" class="space-y-2">
                <div class="flex gap-1" id="estrelas-interativas">
                    <input type="hidden" name="nota" :value="nota">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                        @click="nota = {{ $i }}"
                        @mouseenter="hover = {{ $i }}"
                        @mouseleave="hover = 0"
                        class="text-4xl transition-transform hover:scale-110 focus:outline-none">
                        <span :class="(hover || nota) >= {{ $i }} ? 'star-filled' : 'star-empty'">★</span>
                        </button>
                        @endfor
                </div>
                <p class="text-sm text-gray-500 h-5">
                    <span x-show="hover === 1 || (hover === 0 && nota === 1)">😤 Péssimo</span>
                    <span x-show="hover === 2 || (hover === 0 && nota === 2)">😕 Ruim</span>
                    <span x-show="hover === 3 || (hover === 0 && nota === 3)">😐 Regular</span>
                    <span x-show="hover === 4 || (hover === 0 && nota === 4)">😊 Bom</span>
                    <span x-show="hover === 5 || (hover === 0 && nota === 5)">🤩 Excelente!</span>
                </p>
            </div>
            @error('nota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Título --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Título da avaliação <span class="text-red-500">*</span>
            </label>
            <input type="text" name="titulo" value="{{ old('titulo') }}"
                placeholder="Resuma sua experiência em uma frase"
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                          {{ $errors->has('titulo') ? 'border-red-400' : 'border-gray-200' }}">
            @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Conteúdo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Avaliação completa <span class="text-red-500">*</span>
            </label>
            <textarea name="conteudo" rows="5"
                placeholder="Conte sua experiência: qualidade, durabilidade, custo-benefício, entrega, embalagem..."
                class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none
                             {{ $errors->has('conteudo') ? 'border-red-400' : 'border-gray-200' }}">{{ old('conteudo') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Mínimo 20 caracteres</p>
            @error('conteudo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Detalhes de compra --}}
        <div class="border border-gray-100 rounded-xl p-5 space-y-4 bg-gray-50">
            <p class="text-sm font-medium text-gray-700">🛒 Detalhes da compra <span class="text-gray-400 font-normal">(opcionais, mas muito úteis!)</span></p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Preço pago (R$)</label>
                    <input type="number" name="preco_pago" value="{{ old('preco_pago') }}"
                        step="0.01" min="0" placeholder="Ex: 249.90"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                    @error('preco_pago') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Loja / Site</label>
                    <input type="text" name="loja" value="{{ old('loja') }}"
                        placeholder="Ex: Amazon, Shopee..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Link da loja (URL)</label>
                <input type="url" name="url_loja" value="{{ old('url_loja') }}"
                    placeholder="https://..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-white">
                @error('url_loja') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Recomenda? --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                Você recomenda este produto? <span class="text-red-500">*</span>
            </label>
            <div class="flex gap-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recomenda" value="1"
                        {{ old('recomenda', '1') === '1' ? 'checked' : '' }}
                        class="accent-green-500">
                    <span class="text-sm">👍 Sim, recomendo</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="recomenda" value="0"
                        {{ old('recomenda') === '0' ? 'checked' : '' }}
                        class="accent-red-500">
                    <span class="text-sm">👎 Não recomendo</span>
                </label>
            </div>
        </div>

        {{-- Imagens da avaliação --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fotos do produto <span class="text-gray-400 text-xs">(opcional, até 5 imagens)</span>
            </label>
            <p class="text-xs text-gray-400 mb-3">
                Adicione fotos reais do produto que você recebeu. JPG, PNG ou WEBP · Máx. 2MB cada
            </p>

            <input type="file" name="imagens[]" accept="image/*" multiple
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full
                  file:border-0 file:text-sm file:font-medium
                  file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                onchange="previewImagens(this)">

            @error('imagens')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('imagens.*')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            {{-- Preview das imagens selecionadas --}}
            <div id="preview-imagens" class="flex flex-wrap gap-2 mt-3"></div>
        </div>

        <div class="flex gap-4 pt-2">
            <button type="submit"
                class="flex-1 bg-amber-400 hover:bg-amber-500 text-white py-3 rounded-xl font-medium transition">
                ⭐ Publicar avaliação
            </button>
            <a href="{{ route('produtos.show', $produto) }}"
                class="flex-1 text-center border border-gray-200 text-gray-600 hover:bg-gray-50 py-3 rounded-xl text-sm transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection