@extends('layouts.app')
@section('title', 'Início')

@section('content')

<!-- HERO -->
<section class="rh-hero">
    <div class="container text-center">
        <h1 class="display-5 fw-800 mb-3">Reviews reais de produtos reais</h1>
        <p class="mb-4 fs-sm" style="opacity:.9; max-width:520px; margin:0 auto 1.5rem;">
            Antes de comprar, descubra o que outras pessoas acharam.
            Preço pago, loja, nota e muito mais — tudo num lugar só.
        </p>

        <!-- Busca -->
        <form action="{{ route('produtos.index') }}" method="GET" class="mb-5">
            <div class="rh-hero-search">
                <input type="text" name="busca" placeholder="Ex: iPhone 15, tênis Nike, cafeteira Nespresso...">
                <button type="submit" class="btn-rh-primary">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </form>

        <!-- Estatísticas -->
        <div class="d-flex justify-content-center gap-5">
            <div class="rh-hero-stat">
                <div class="rh-stat-number">{{ number_format($totalProdutos) }}</div>
                <div class="rh-stat-label">Produtos</div>
            </div>
            <div style="border-left: 1px solid rgba(255,255,255,.3)"></div>
            <div class="rh-hero-stat">
                <div class="rh-stat-number">{{ number_format($totalAvaliacoes) }}</div>
                <div class="rh-stat-label">Avaliações</div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">

    <!-- Categorias -->
    <section class="mb-5">
        <div class="rh-section-header">
            <h2 class="rh-section-title">
                <i class="bi bi-grid-3x3-gap text-rh-primary me-1"></i> Explorar por categoria
            </h2>
        </div>
        <div class="row row-cols-2 row-cols-sm-4 row-cols-md-5 row-cols-lg-10 g-2">
            @foreach($categorias as $categoria)
                <div class="col">
                    <a href="{{ route('produtos.index', ['categoria' => $categoria->slug]) }}"
                       class="rh-category-pill w-100">
                        <span class="rh-cat-icon">{{ $categoria->icone }}</span>
                        <span>{{ $categoria->nome }}</span>
                        <span class="rh-cat-count">{{ $categoria->produtos_count }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Melhor avaliados -->
    @if($melhorNotados->isNotEmpty())
    <section class="mb-5">
        <div class="rh-section-header">
            <h2 class="rh-section-title">
                <i class="bi bi-star-fill text-rh-accent me-1"></i> Melhor avaliados
            </h2>
            <a href="{{ route('produtos.index', ['ordem' => 'melhor_nota']) }}" class="fs-sm">
                Ver todos <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach($melhorNotados as $produto)
                <div class="col">
                    <x-produto-card :produto="$produto" />
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Mais avaliados -->
    @if($maisAvaliados->isNotEmpty())
    <section class="mb-5">
        <div class="rh-section-header">
            <h2 class="rh-section-title">
                <i class="bi bi-fire text-danger me-1"></i> Mais avaliados
            </h2>
            <a href="{{ route('produtos.index', ['ordem' => 'mais_avaliados']) }}" class="fs-sm">
                Ver todos <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-3">
            @foreach($maisAvaliados as $produto)
                <div class="col">
                    <x-produto-card :produto="$produto" />
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Recentes + CTA -->
    <section>
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="rh-section-header">
                    <h2 class="rh-section-title">
                        <i class="bi bi-clock-history text-rh-primary me-1"></i> Recém adicionados
                    </h2>
                    <a href="{{ route('produtos.index') }}" class="fs-sm">
                        Ver todos <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="row row-cols-2 row-cols-sm-4 g-3">
                    @foreach($recemAdicionados as $produto)
                        <div class="col">
                            <x-produto-card :produto="$produto" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- CTA -->
            <div class="col-lg-4">
                <div class="rh-card h-100 d-flex flex-column justify-content-center align-items-center text-center p-4"
                     style="background: linear-gradient(135deg, var(--rh-primary-light), #ede9fe);">
                    <i class="bi bi-box-seam" style="font-size:3rem; color: var(--rh-primary);"></i>
                    <h4 class="fw-700 mt-3 mb-2">Não achou o produto?</h4>
                    <p class="text-rh-muted fs-sm mb-4">
                        Cadastre-o e seja o primeiro a avaliar. Ajude outras pessoas a decidirem com segurança!
                    </p>
                    <a href="{{ route('produtos.create') }}" class="btn-rh-primary w-100 justify-content-center">
                        <i class="bi bi-plus-lg"></i> Cadastrar Produto
                    </a>
                    @guest
                        <p class="fs-xs text-rh-muted mt-2 mb-0">É necessário ter uma conta.</p>
                    @endguest
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
