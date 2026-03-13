@extends('layouts.app')
@section('title', 'Editar: ' . $produto->nome)

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8">✏️ Editar produto</h1>

    <form action="{{ route('produtos.update', $produto) }}" method="POST" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        {{--
            CONCEITO: @method('PUT')
            HTML só suporta GET e POST.
            O Laravel usa um campo hidden _method para simular PUT/PATCH/DELETE.
            Route::put() no servidor detecta e roteia corretamente.
        --}}
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
            <input type="text" name="nome" value="{{ old('nome', $produto->nome) }}"
                   class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                          {{ $errors->has('nome') ? 'border-red-400' : 'border-gray-200' }}">
            @error('nome') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <input type="text" name="marca" value="{{ old('marca', $produto->marca) }}"
                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria *</label>
            <select name="categoria_id"
                    class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                           {{ $errors->has('categoria_id') ? 'border-red-400' : 'border-gray-200' }}">
                @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ old('categoria_id', $produto->categoria_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->icone }} {{ $cat->nome }}
                    </option>
                @endforeach
            </select>
            @error('categoria_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
            <textarea name="descricao" rows="3"
                      class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 resize-none">{{ old('descricao', $produto->descricao) }}</textarea>
        </div>

        {{-- Imagem atual + novo upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagem</label>
            <div class="flex items-center gap-4 mb-3">
                <img src="{{ $produto->imagem_url }}" alt="Atual"
                     class="w-20 h-20 object-cover rounded-xl border border-gray-200">
                <p class="text-xs text-gray-500">Imagem atual. Selecione uma nova para substituir.</p>
            </div>
            <input type="file" name="imagem" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                          file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('imagem') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-4 pt-2">
            <button type="submit"
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-medium transition">
                Salvar alterações
            </button>
            <a href="{{ route('produtos.show', $produto) }}"
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
