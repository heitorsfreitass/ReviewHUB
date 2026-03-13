@extends('layouts.app')
@section('title', 'Editar avaliação')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="rh-card p-3 mb-4 d-flex align-items-center gap-3">
                <img src="{{ $produto->imagem_url }}" alt="{{ $produto->nome }}"
                    style="width:64px;height:64px;object-fit:cover;border-radius:var(--rh-radius);">
                <div>
                    <div class="fw-700 h5 mb-0">Editar avaliação</div>
                    <div class="fs-sm text-rh-muted">{{ $produto->nome }}</div>
                </div>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('produtos.avaliacoes.update', [$produto, $avaliacao]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nota -->
                    <div class="mb-4">
                        <label class="rh-form-label">Nota <span class="text-danger">*</span></label>
                        <input type="hidden" name="nota" id="nota-input" value="{{ old('nota', $avaliacao->nota) }}">
                        <div class="star-picker" id="star-picker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-valor="{{ $i }}">★</button>
                                @endfor
                        </div>
                        <div class="fs-sm text-rh-muted mt-1" id="nota-label" style="height:1.4em;"></div>
                        @error('nota') <div class="rh-invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- Título -->
                    <div class="mb-4">
                        <label class="rh-form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" value="{{ old('titulo', $avaliacao->titulo) }}"
                            class="rh-form-control {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                        @error('titulo') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Conteúdo -->
                    <div class="mb-4">
                        <label class="rh-form-label">Avaliação <span class="text-danger">*</span></label>
                        <textarea name="conteudo" rows="5" style="resize:none;"
                            class="rh-form-control {{ $errors->has('conteudo') ? 'is-invalid' : '' }}">{{ old('conteudo', $avaliacao->conteudo) }}</textarea>
                        @error('conteudo') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Detalhes compra -->
                    <div class="mb-4 p-3" style="background:var(--rh-bg);border-radius:var(--rh-radius);border:1px solid var(--rh-border);">
                        <div class="fw-600 fs-sm mb-3">
                            <i class="bi bi-cart3 text-rh-primary me-1"></i> Detalhes da compra
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="rh-form-label">Preço pago (R$)</label>
                                <input type="number" name="preco_pago" step="0.01" min="0"
                                    value="{{ old('preco_pago', $avaliacao->preco_pago) }}"
                                    class="rh-form-control">
                            </div>
                            <div class="col-sm-6">
                                <label class="rh-form-label">Loja</label>
                                <input type="text" name="loja"
                                    value="{{ old('loja', $avaliacao->loja) }}"
                                    class="rh-form-control">
                            </div>
                            <div class="col-12">
                                <label class="rh-form-label">Link da loja</label>
                                <input type="url" name="url_loja"
                                    value="{{ old('url_loja', $avaliacao->url_loja) }}"
                                    class="rh-form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Fotos -->
                    <div class="mb-4">
                        <label class="rh-form-label">Fotos da avaliação</label>

                        @if($avaliacao->imagens_urls)
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            @foreach($avaliacao->imagens_urls as $url)
                            <img src="{{ $url }}" alt="Foto"
                                style="width:72px;height:72px;object-fit:cover;border-radius:var(--rh-radius);border:1px solid var(--rh-border);">
                            @endforeach
                        </div>
                        <p class="fs-xs text-rh-muted mb-2">Selecione novas imagens para substituir as atuais.</p>
                        @endif

                        <input type="file" name="imagens[]" accept="image/*" multiple
                            class="form-control fs-sm" onchange="previewImagens(this)">
                        <div class="rh-image-preview-grid mt-2" id="preview-grid"></div>
                    </div>

                    <!-- Recomenda -->
                    <div class="mb-4">
                        <label class="rh-form-label">Recomenda? <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomenda"
                                    id="rec-sim" value="1"
                                    {{ old('recomenda', $avaliacao->recomenda ? '1' : '0') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fs-sm" for="rec-sim">
                                    <i class="bi bi-hand-thumbs-up-fill text-success"></i> Sim
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomenda"
                                    id="rec-nao" value="0"
                                    {{ old('recomenda', $avaliacao->recomenda ? '1' : '0') === '0' ? 'checked' : '' }}>
                                <label class="form-check-label fs-sm" for="rec-nao">
                                    <i class="bi bi-hand-thumbs-down-fill text-danger"></i> Não
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-rh-primary flex-fill justify-content-center py-2">
                            <i class="bi bi-check-lg"></i> Salvar
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

@push('scripts')
<script>
    const labels = ['', '😤 Péssimo', '😕 Ruim', '😐 Regular', '😊 Bom', '🤩 Excelente!'];
    const picker = document.getElementById('star-picker');
    const input = document.getElementById('nota-input');
    const label = document.getElementById('nota-label');
    let current = parseInt(input.value) || 0;

    function paintStars(n) {
        picker.querySelectorAll('.star-btn').forEach((btn, i) => btn.classList.toggle('active', i < n));
    }

    picker.querySelectorAll('.star-btn').forEach(btn => {
        const v = parseInt(btn.dataset.valor);
        btn.addEventListener('mouseenter', () => {
            paintStars(v);
            label.textContent = labels[v];
        });
        btn.addEventListener('mouseleave', () => {
            paintStars(current);
            label.textContent = labels[current];
        });
        btn.addEventListener('click', () => {
            current = v;
            input.value = v;
            paintStars(v);
            label.textContent = labels[v];
        });
    });

    paintStars(current);
    label.textContent = labels[current] || '';

    function previewImagens(input) {
        const grid = document.getElementById('preview-grid');
        grid.innerHTML = '';
        Array.from(input.files).forEach(file => {
            const r = new FileReader();
            r.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                grid.appendChild(img);
            };
            r.readAsDataURL(file);
        });
    }
</script>
@endpush