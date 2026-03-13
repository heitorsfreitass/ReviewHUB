@extends('layouts.app')
@section('title', $produto->nome)

@section('content')
<div class="container py-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb fs-sm">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produtos.index') }}">Produtos</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('produtos.index', ['categoria' => $produto->categoria->slug]) }}">
                    {{ $produto->categoria->nome }}
                </a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width:200px">
                {{ $produto->nome }}
            </li>
        </ol>
    </nav>

    <!-- PRODUTO HEADER -->
    <div class="rh-card mb-4">
        <div class="row g-0">

            <!-- Imagem -->
            <div class="col-md-4">
                <img src="{{ $produto->imagem_url }}"
                    alt="{{ $produto->nome }}"
                    class="w-100 h-100 rounded-start"
                    style="object-fit:cover; max-height:360px;"
                    onerror="this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=Sem+Foto'">
            </div>

            <!-- Detalhes -->
            <div class="col-md-8 p-4 d-flex flex-column">

                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div>
                        <span class="rh-badge rh-badge-category mb-2">
                            {{ $produto->categoria->icone }} {{ $produto->categoria->nome }}
                        </span>
                        <h1 class="h3 fw-700 mb-1">{{ $produto->nome }}</h1>
                        @if($produto->marca)
                        <p class="text-rh-muted fs-sm mb-0">por <strong>{{ $produto->marca }}</strong></p>
                        @endif
                    </div>

                    @can('update', $produto)
                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="{{ route('produtos.edit', $produto) }}" class="btn-rh-ghost btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST"
                            onsubmit="return confirm('Excluir produto e todas suas avaliações?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>

                @if($produto->descricao)
                <p class="text-rh-muted fs-sm mt-3">{{ $produto->descricao }}</p>
                @endif

                <!-- Nota geral + distribuição -->
                <div class="row align-items-center mt-4 g-3">
                    <div class="col-auto text-center">
                        <div class="fw-800" style="font-size:3rem; line-height:1; color:var(--rh-text)">
                            {{ $produto->total_avaliacoes > 0 ? number_format($produto->media_nota, 1) : '—' }}
                        </div>
                        <x-estrelas :nota="$produto->media_nota" tamanho="md" class="mt-1" />
                        <div class="fs-xs text-rh-muted mt-1">
                            {{ $produto->total_avaliacoes }} avaliação(ões)
                        </div>
                    </div>

                    @if($produto->total_avaliacoes > 0)
                    <div class="col">
                        @foreach([5,4,3,2,1] as $n)
                        @php $qtd = $distribuicaoNotas->get($n, 0); @endphp
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fs-xs text-rh-muted" style="width:14px">{{ $n }}</span>
                            <i class="bi bi-star-fill" style="color:var(--rh-accent); font-size:.75rem;"></i>
                            <div class="rh-rating-bar-track">
                                <div class="rh-rating-bar-fill"
                                    style="width:{{ $produto->total_avaliacoes > 0 ? ($qtd/$produto->total_avaliacoes)*100 : 0 }}%">
                                </div>
                            </div>
                            <span class="fs-xs text-rh-muted" style="width:18px">{{ $qtd }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Botão avaliar -->
                <div class="mt-auto pt-3">
                    @auth
                    @if($jaAvaliou)
                    @php $minhaAv = $produto->avaliacoes->firstWhere('user_id', auth()->id()); @endphp
                    <span class="text-rh-muted fs-sm">
                        <i class="bi bi-check-circle-fill text-success"></i> Você já avaliou
                        @if($minhaAv)
                        &middot; <a href="{{ route('produtos.avaliacoes.edit', [$produto, $minhaAv]) }}">Editar</a>
                        @endif
                    </span>
                    @else
                    <a href="{{ route('produtos.avaliacoes.create', $produto) }}" class="btn-rh-accent">
                        <i class="bi bi-star"></i> Escrever avaliação
                    </a>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="btn-rh-accent">
                        <i class="bi bi-star"></i> Entrar para avaliar
                    </a>
                    @endauth
                </div>

                <p class="fs-xs text-rh-muted mt-3 mb-0">
                    Cadastrado por <strong>{{ $produto->user->name }}</strong>
                    {{ $produto->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    <!-- AVALIAÇÕES -->
    <h2 class="h5 fw-700 mb-4">
        <i class="bi bi-chat-square-text text-rh-primary me-1"></i>
        Avaliações ({{ $produto->total_avaliacoes }})
    </h2>

    @forelse($produto->avaliacoes as $avaliacao)
    <div class="rh-review-card">
        <div class="d-flex align-items-start justify-content-between gap-3">
            <!-- Autor -->
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $avaliacao->user->avatar_url }}"
                    alt="{{ $avaliacao->user->name }}"
                    class="rh-reviewer-avatar">
                <div>
                    <div class="fw-600 fs-sm">{{ $avaliacao->user->name }}</div>
                    <div class="fs-xs text-rh-muted">{{ $avaliacao->created_at->diffForHumans() }}</div>
                </div>
            </div>

            @can('update', $avaliacao)
            <div class="d-flex gap-2">
                <a href="{{ route('produtos.avaliacoes.edit', [$produto, $avaliacao]) }}"
                    class="btn-rh-ghost btn-sm" style="padding:.3rem .7rem; font-size:.8rem;">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('produtos.avaliacoes.destroy', [$produto, $avaliacao]) }}"
                    method="POST" onsubmit="return confirm('Remover avaliação?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px; font-size:.8rem;">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
            @endcan
        </div>

        <!-- Nota + título -->
        <div class="mt-3 d-flex align-items-center flex-wrap gap-2">
            <x-estrelas :nota="$avaliacao->nota" tamanho="sm" />
            <strong class="fs-sm">{{ $avaliacao->titulo }}</strong>
            <span class="rh-badge {{ $avaliacao->recomenda ? 'rh-badge-recommends' : 'rh-badge-no-recommends' }}">
                <i class="bi bi-hand-thumbs-{{ $avaliacao->recomenda ? 'up' : 'down' }}-fill"></i>
                {{ $avaliacao->recomenda ? 'Recomenda' : 'Não recomenda' }}
            </span>
        </div>

        <!-- Meta de compra -->
        @if($avaliacao->preco_pago || $avaliacao->loja)
        <div class="rh-review-meta">
            @if($avaliacao->preco_pago)
            <span>
                <i class="bi bi-currency-dollar"></i>
                Pagou <strong class="text-rh-text">{{ $avaliacao->preco_pago_formatado }}</strong>
            </span>
            @endif
            @if($avaliacao->loja)
            <span>
                <i class="bi bi-shop"></i>
                @if($avaliacao->url_loja)
                <a href="{{ $avaliacao->url_loja }}" target="_blank" rel="noopener">
                    {{ $avaliacao->loja }} <i class="bi bi-box-arrow-up-right" style="font-size:.7rem;"></i>
                </a>
                @else
                {{ $avaliacao->loja }}
                @endif
            </span>
            @endif
        </div>
        @endif

        <!-- Conteúdo -->
        <p class="fs-sm mt-3 mb-0" style="line-height:1.7; color:#374151;">
            {{ $avaliacao->conteudo }}
        </p>

        <!-- Imagens -->
        @if($avaliacao->imagens_urls)
        <div class="rh-review-images">
            @foreach($avaliacao->imagens_urls as $url)
            <a href="{{ $url }}" target="_blank">
                <img src="{{ $url }}" alt="Foto" class="rh-review-img">
            </a>
            @endforeach
        </div>
        @endif

        <!-- Voto útil -->
        <div class="d-flex align-items-center gap-2 mt-3">
            <span class="fs-xs text-rh-muted">Útil?</span>
            @auth
            <form action="{{ route('avaliacoes.util', $avaliacao) }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn-util {{ in_array($avaliacao->id, $votosDoUsuario) ? 'voted' : '' }}">
                    <i class="bi bi-hand-thumbs-up"></i> Sim ({{ $avaliacao->votos_uteis }})
                </button>
            </form>
            @else
            <span class="btn-util" style="cursor:default;">
                <i class="bi bi-hand-thumbs-up"></i> {{ $avaliacao->votos_uteis }}
            </span>
            @endauth
        </div>
    </div>
    @empty
    <div class="rh-card text-center py-5">
        <i class="bi bi-pencil-square" style="font-size:3rem; color:var(--rh-muted);"></i>
        <h4 class="fw-700 mt-3 mb-2">Seja o primeiro a avaliar!</h4>
        <p class="text-rh-muted fs-sm mb-4">Sua opinião ajuda outras pessoas a decidirem.</p>
        @auth
        <a href="{{ route('produtos.avaliacoes.create', $produto) }}" class="btn-rh-accent">
            <i class="bi bi-star"></i> Avaliar agora
        </a>
        @else
        <a href="{{ route('login') }}" class="btn-rh-primary">Entrar para avaliar</a>
        @endauth
    </div>
    @endforelse

</div>
@endsection