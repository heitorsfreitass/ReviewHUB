@extends('layouts.app')

@section('title', 'Cadastrar Produto')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold">📦 Cadastrar novo produto</h1>
        <p class="text-gray-500 mt-1">Não achou o produto que você quer avaliar? Cadastre-o aqui.</p>
    </div>

    {{--
        CONCEITO: enctype="multipart/form-data"
        Obrigatório para formulários com upload de arquivo.
        Sem isso, o arquivo não é enviado ao servidor.
    --}}
    <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        {{--
            CONCEITO: @csrf
            Proteção contra Cross-Site Request Forgery.
            Gera um campo hidden com token único por sessão.
            O Laravel valida esse token em todo POST/PUT/DELETE.
            SEM @csrf o formulário retorna erro 419.
        --}}
        @csrf

        {{-- Nome --}}
        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                Nome do produto <span class="text-red-500">*</span>
            </label>
            {{--
                CONCEITO: old('campo')
                Recupera o valor enviado anteriormente se a validação falhar.
                O usuário não perde o que digitou.
            --}}
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                   placeholder="Ex: iPhone 15 Pro Max, Tênis Nike Air Max..."
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                          {{ $errors->has('nome') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
            {{--
                CONCEITO: $errors e @error
                $errors é uma ViewErrorBag injetada automaticamente pelo Laravel.
                @error('campo') exibe a mensagem de erro do campo específico.
            --}}
            @error('nome')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Marca --}}
        <div>
            <label for="marca" class="block text-sm font-medium text-gray-700 mb-1">
                Marca <span class="text-gray-400 text-xs">(opcional)</span>
            </label>
            <input type="text" id="marca" name="marca" value="{{ old('marca') }}"
                   placeholder="Ex: Apple, Samsung, Nike..."
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
            @error('marca')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Categoria --}}
        <div>
            <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-1">
                Categoria <span class="text-red-500">*</span>
            </label>
            <select id="categoria_id" name="categoria_id"
                    class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                           {{ $errors->has('categoria_id') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                <option value="">Selecione uma categoria...</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}"
                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->icone }} {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Descrição --}}
        <div>
            <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">
                Descrição breve <span class="text-gray-400 text-xs">(opcional)</span>
            </label>
            <textarea id="descricao" name="descricao" rows="3"
                      placeholder="O que é esse produto? Para que serve?"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('descricao') }}</textarea>
            @error('descricao')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload de imagem --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Foto do produto <span class="text-red-500">*</span>
            </label>
            <p class="text-xs text-gray-400 mb-3">
                Use uma foto clara do produto para facilitar identificação. JPG, PNG ou WEBP · Máx. 4MB
            </p>

            {{-- Preview da imagem antes do upload --}}
            <div id="preview-container" class="hidden mb-3">
                <img id="preview-img" src="" alt="Preview"
                     class="w-32 h-32 object-cover rounded-xl border border-gray-200">
            </div>

            <label for="imagem"
                   class="flex items-center justify-center gap-3 w-full border-2 border-dashed rounded-xl px-4 py-8 cursor-pointer transition
                          {{ $errors->has('imagem') ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:border-indigo-400 hover:bg-indigo-50' }}">
                <span class="text-3xl">📸</span>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-700" id="upload-label">Clique para selecionar a imagem</p>
                    <p class="text-xs text-gray-400">ou arraste e solte aqui</p>
                </div>
                <input type="file" id="imagem" name="imagem" accept="image/*" class="hidden"
                       onchange="previewImagem(this)">
            </label>
            @error('imagem')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botões --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition">
                Cadastrar Produto
            </button>
            <a href="{{ route('produtos.index') }}"
               class="flex-1 text-center border border-gray-200 text-gray-600 hover:bg-gray-50 py-3 rounded-xl text-sm transition">
                Cancelar
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewImagens(input) {
    const container = document.getElementById('preview-imagens');
    container.innerHTML = '';

    if (input.files.length > 5) {
        alert('Máximo de 5 imagens.');
        input.value = '';
        return;
    }

    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-20 h-20 object-cover rounded-xl border border-gray-200';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endpush
@endsection
