@extends('layouts.app')
@section('title', 'Avaliar: ' . $produto->nome)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Info do produto -->
            <div class="rh-card p-3 mb-4 d-flex align-items-center gap-3">
                <img src="{{ $produto->imagem_url }}" alt="{{ $produto->nome }}"
                    style="width:64px;height:64px;object-fit:cover;border-radius:var(--rh-radius);"
                    onerror="this.src='https://placehold.co/100x100/f3f4f6/9ca3af?text=?'">
                <div>
                    <div class="fs-xs text-rh-primary fw-600">{{ $produto->categoria->nome }}</div>
                    <div class="fw-700">{{ $produto->nome }}</div>
                    @if($produto->marca)
                    <div class="fs-xs text-rh-muted">{{ $produto->marca }}</div>
                    @endif
                </div>
            </div>

            <div class="rh-card p-4 p-md-5">
                <form action="{{ route('produtos.avaliacoes.store', $produto) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- NOTA COM STAR PICKER -->
                    <div class="mb-4">
                        <label class="rh-form-label">Sua nota <span class="text-danger">*</span></label>
                        <input type="hidden" name="nota" id="nota-input" value="{{ old('nota', 0) }}">

                        <div class="star-picker" id="star-picker">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-valor="{{ $i }}">★</button>
                                @endfor
                        </div>

                        <div class="fs-sm text-rh-muted mt-1" id="nota-label" style="height:1.4em;">
                            @if(old('nota'))
                            {{ ['','😤 Péssimo','😕 Ruim','😐 Regular','😊 Bom','🤩 Excelente!'][old('nota')] }}
                            @endif
                        </div>

                        @error('nota')
                        <div class="rh-invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Título -->
                    <div class="mb-4">
                        <label class="rh-form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}"
                            placeholder="Resuma sua experiência em uma frase"
                            class="rh-form-control {{ $errors->has('titulo') ? 'is-invalid' : '' }}">
                        @error('titulo') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Conteúdo -->
                    <div class="mb-4">
                        <label class="rh-form-label">Avaliação completa <span class="text-danger">*</span></label>
                        <textarea name="conteudo" rows="5" style="resize:none;"
                            placeholder="Qualidade, durabilidade, custo-benefício, entrega..."
                            class="rh-form-control {{ $errors->has('conteudo') ? 'is-invalid' : '' }}">{{ old('conteudo') }}</textarea>
                        <div class="fs-xs text-rh-muted mt-1">Mínimo 20 caracteres</div>
                        @error('conteudo') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Detalhes de compra -->
                    <div class="mb-4 p-3" style="background:var(--rh-bg); border-radius:var(--rh-radius); border:1px solid var(--rh-border);">
                        <div class="fw-600 fs-sm mb-3">
                            <i class="bi bi-cart3 text-rh-primary me-1"></i>
                            Detalhes da compra
                            <span class="text-rh-muted fw-400">(opcional, mas muito úteis!)</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="rh-form-label">Preço pago (R$)</label>
                                <input type="number" name="preco_pago" value="{{ old('preco_pago') }}"
                                    step="0.01" min="0" placeholder="Ex: 249.90"
                                    class="rh-form-control">
                            </div>
                            <div class="col-sm-6">
                                <label class="rh-form-label">Loja / Site</label>
                                <input type="text" name="loja" value="{{ old('loja') }}"
                                    placeholder="Ex: Amazon, Shopee..."
                                    class="rh-form-control">
                            </div>
                            <div class="col-12">
                                <label class="rh-form-label">Link da loja</label>
                                <input type="url" name="url_loja" value="{{ old('url_loja') }}"
                                    placeholder="https://..."
                                    class="rh-form-control {{ $errors->has('url_loja') ? 'is-invalid' : '' }}">
                                @error('url_loja') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Fotos -->
                    <div class="mb-4">
                        <label class="rh-form-label">
                            Fotos do produto
                            <span class="text-rh-muted fw-400 fs-xs">(opcional, até 5 imagens)</span>
                        </label>
                        <p class="fs-xs text-rh-muted mb-2">JPG, PNG ou WEBP &middot; Máx. 2MB cada</p>

                        <label for="imagens-input" class="rh-upload-zone w-100" id="upload-zone">
                            <i class="bi bi-images" style="font-size:1.8rem;color:var(--rh-primary);"></i>
                            <p class="fw-600 mt-2 mb-0">Clique para selecionar</p>
                            <p class="fs-xs text-rh-muted mb-0">ou arraste e solte aqui</p>
                            <input type="file" id="imagens-input" name="imagens[]"
                                accept="image/*" multiple onchange="previewImagens(this)">
                        </label>

                        @error('imagens')
                        <div class="rh-invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('imagens.*')
                        <div class="rh-invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div class="rh-image-preview-grid" id="preview-grid"></div>
                    </div>

                    <!-- Recomenda? -->
                    <div class="mb-4">
                        <label class="rh-form-label">Você recomenda? <span class="text-danger">*</span></label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomenda"
                                    id="rec-sim" value="1"
                                    {{ old('recomenda', '1') === '1' ? 'checked' : '' }}>
                                <label class="form-check-label fs-sm" for="rec-sim">
                                    <i class="bi bi-hand-thumbs-up-fill text-success"></i> Sim, recomendo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recomenda"
                                    id="rec-nao" value="0"
                                    {{ old('recomenda') === '0' ? 'checked' : '' }}>
                                <label class="form-check-label fs-sm" for="rec-nao">
                                    <i class="bi bi-hand-thumbs-down-fill text-danger"></i> Não recomendo
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="d-flex gap-3">
                        <button type="submit" class="btn-rh-accent flex-fill justify-content-center py-2">
                            <i class="bi bi-star"></i> Publicar avaliação
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
        picker.querySelectorAll('.star-btn').forEach((btn, i) => {
            btn.classList.toggle('active', i < n);
        });
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

    const zone = document.getElementById('upload-zone');

    zone.addEventListener('dragover', e => {
        e.preventDefault();
        zone.classList.add('dragover');
    });
    zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    function previewImagens(input) {
        handleFiles(input.files);
    }

    function handleFiles(files) {
        if (files.length > 5) {
            alert('Máximo de 5 imagens.');
            return;
        }
        const grid = document.getElementById('preview-grid');
        grid.innerHTML = '';
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.createElement('img');
                img.src = e.target.result;
                grid.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endpush