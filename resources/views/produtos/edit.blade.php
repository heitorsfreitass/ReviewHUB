@extends('layouts.app')
@section('title', 'Editar: ' . $produto->nome)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4">
                <h1 class="h3 fw-700">
                    <i class="bi bi-pencil text-rh-primary me-2"></i>Editar produto
                </h1>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('produtos.update', $produto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="rh-form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" value="{{ old('nome', $produto->nome) }}"
                            class="rh-form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}">
                        @error('nome') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Marca</label>
                        <input type="text" name="marca" value="{{ old('marca', $produto->marca) }}"
                            class="rh-form-control">
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Categoria <span class="text-danger">*</span></label>
                        <select name="categoria_id"
                            class="rh-form-control {{ $errors->has('categoria_id') ? 'is-invalid' : '' }}">
                            @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('categoria_id', $produto->categoria_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->icone }} {{ $cat->nome }}
                            </option>
                            @endforeach
                        </select>
                        @error('categoria_id') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="rh-form-label">Descrição</label>
                        <textarea name="descricao" rows="3" class="rh-form-control"
                            style="resize:none;">{{ old('descricao', $produto->descricao) }}</textarea>
                    </div>

                    <!-- Imagem atual + troca -->
                    <div class="mb-4">
                        <label class="rh-form-label">Imagem</label>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $produto->imagem_url }}" alt="Atual"
                                style="width:80px;height:80px;object-fit:cover;border-radius:var(--rh-radius);border:1px solid var(--rh-border);">
                            <p class="fs-xs text-rh-muted mb-0">Imagem atual.<br>Selecione uma nova para substituir.</p>
                        </div>
                        <input type="file" name="imagem" accept="image/*" class="form-control fs-sm">
                        @error('imagem') <div class="rh-invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <button type="submit" class="btn-rh-primary flex-fill justify-content-center py-2">
                            <i class="bi bi-check-lg"></i> Salvar alterações
                        </button>
                        <a href="{{ route('produtos.show', $produto) }}"
                            class="btn-rh-ghost flex-fill justify-content-center py-2">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection