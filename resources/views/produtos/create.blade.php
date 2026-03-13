@extends('layouts.app')
@section('title', 'Cadastrar Produto')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="h3 fw-700">
                    <i class="bi bi-box-seam text-rh-primary me-2"></i>Cadastrar novo produto
                </h1>
                <p class="text-rh-muted fs-sm">Não achou o produto que quer avaliar? Cadastre-o aqui.</p>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Nome -->
                    <div class="mb-4">
                        <label class="rh-form-label">Nome do produto <span class="text-danger">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome') }}"
                            placeholder="Ex: iPhone 15 Pro Max, Tênis Nike Air Max..."
                            class="rh-form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}">
                        @error('nome')
                        <div class="rh-invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div class="mb-4">
                        <label class="rh-form-label">
                            Marca <span class="text-rh-muted fw-400 fs-xs">(opcional)</span>
                        </label>
                        <input type="text" name="marca" value="{{ old('marca') }}"
                            placeholder="Ex: Apple, Samsung, Nike..."
                            class="rh-form-control">
                    </div>

                    <!-- Categoria -->
                    <div class="mb-4">
                        <label class="rh-form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="categoria_id"
                            class="rh-form-control {{ $errors->has('categoria_id') ? 'is-invalid' : '' }}">
                            <option value="">Selecione uma categoria...</option>
                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->icone }} {{ $categoria->nome }}
                            </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                        <div class="rh-invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <label class="rh-form-label">
                            Descrição <span class="text-rh-muted fw-400 fs-xs">(opcional)</span>
                        </label>
                        <textarea name="descricao" rows="3"
                            placeholder="O que é esse produto? Para que serve?"
                            class="rh-form-control" style="resize:none;">{{ old('descricao') }}</textarea>
                    </div>

                    <!-- Upload imagem -->
                    <div class="mb-4">
                        <label class="rh-form-label">Foto do produto <span class="text-danger">*</span></label>
                        <p class="fs-xs text-rh-muted mb-2">
                            JPG, PNG ou WEBP &middot; Máx. 4MB
                        </p>

                        <!-- Preview -->
                        <div id="preview-wrap" class="d-none mb-3">
                            <img id="preview-img" src="" alt="Preview"
                                style="width:100px; height:100px; object-fit:cover; border-radius:var(--rh-radius); border:1px solid var(--rh-border);">
                        </div>

                        <label for="imagem" class="rh-upload-zone w-100">
                            <i class="bi bi-camera" style="font-size:2rem; color:var(--rh-primary);"></i>
                            <p class="fw-600 mt-2 mb-0" id="upload-label">Clique para selecionar</p>
                            <p class="fs-xs text-rh-muted mb-0">ou arraste e solte aqui</p>
                            <input type="file" id="imagem" name="imagem" accept="image/*"
                                onchange="previewImagem(this)">
                        </label>
                        @error('imagem')
                        <div class="rh-invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botões -->
                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn-rh-primary flex-fill justify-content-center py-2">
                            <i class="bi bi-check-lg"></i> Cadastrar Produto
                        </button>
                        <a href="{{ route('produtos.index') }}" class="btn-rh-ghost flex-fill justify-content-center py-2">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImagem(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-wrap').classList.remove('d-none');
                document.getElementById('upload-label').textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush