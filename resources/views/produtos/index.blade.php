@extends('layouts.app')
@section('title', 'Produtos')

@section('content')
<div class="container py-4">
    <div class="row g-4">

        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <div class="rh-filter-sidebar">
                <form action="{{ route('produtos.index') }}" method="GET">

                    <p class="rh-filter-title">
                        <i class="bi bi-funnel me-1"></i> Filtros
                    </p>

                    <!-- Busca -->
                    <div class="mb-4">
                        <label class="rh-filter-title">Buscar</label>
                        <input type="text" name="busca" value="{{ request('busca') }}"
                            class="rh-form-control" placeholder="Nome, marca...">
                    </div>

                    <!-- Categorias -->
                    <div class="mb-4">
                        <label class="rh-filter-title">Categoria</label>
                        <div style="max-height: 260px; overflow-y: auto;">
                            <a href="{{ route('produtos.index', array_merge(request()->except('categoria','page'), [])) }}"
                                class="rh-filter-link {{ !request('categoria') ? 'active' : '' }}">
                                <span>Todas</span>
                            </a>
                            @foreach($categorias as $cat)
                            <a href="{{ route('produtos.index', array_merge(request()->except('categoria','page'), ['categoria' => $cat->slug])) }}"
                                class="rh-filter-link {{ request('categoria') === $cat->slug ? 'active' : '' }}">
                                <span>{{ $cat->icone }} {{ $cat->nome }}</span>
                                <span class="fs-xs text-rh-muted">{{ $cat->produtos_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Ordenação -->
                    <div class="mb-4">
                        <label class="rh-filter-title">Ordenar por</label>
                        <select name="ordem" class="rh-form-control" onchange="this.form.submit()">
                            <option value="recentes" {{ request('ordem','recentes') === 'recentes'       ? 'selected' : '' }}>Mais recentes</option>
                            <option value="mais_avaliados" {{ request('ordem') === 'mais_avaliados' ? 'selected' : '' }}>Mais avaliados</option>
                            <option value="melhor_nota" {{ request('ordem') === 'melhor_nota'    ? 'selected' : '' }}>Melhor nota</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-rh-primary w-100 justify-content-center">
                        <i class="bi bi-search"></i> Aplicar
                    </button>

                    @if(request()->hasAny(['busca','categoria','ordem']))
                    <a href="{{ route('produtos.index') }}"
                        class="d-block text-center mt-2 fs-xs text-rh-muted">
                        <i class="bi bi-x-circle"></i> Limpar filtros
                    </a>
                    @endif

                </form>
            </div>
        </div>

        <!-- GRID -->
        <div class="col-lg-9">

            <!-- Cabeçalho -->
            <div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="h4 fw-700 mb-0">
                        @if(request('busca'))
                        Resultados para "{{ request('busca') }}"
                        @elseif(request('categoria'))
                        {{ $categorias->firstWhere('slug', request('categoria'))?->icone }}
                        {{ $categorias->firstWhere('slug', request('categoria'))?->nome }}
                        @else
                        Todos os produtos
                        @endif
                    </h1>
                    <p class="fs-xs text-rh-muted mb-0">
                        {{ $produtos->total() }} produto(s) encontrado(s)
                    </p>
                </div>
                <a href="{{ route('produtos.create') }}" class="btn-rh-primary">
                    <i class="bi bi-plus-lg"></i> Cadastrar
                </a>
            </div>

            <!-- Produtos -->
            @forelse($produtos as $produto)
            @if($loop->first)
            <div class="row row-cols-2 row-cols-sm-3 row-cols-xl-4 g-3">
                @endif

                <div class="col">
                    <x-produto-card :produto="$produto" />
                </div>

                @if($loop->last)
            </div>
            @endif
            @empty
            <div class="rh-card text-center py-5 px-4">
                <i class="bi bi-search" style="font-size:3rem; color: var(--rh-muted);"></i>
                <h4 class="fw-700 mt-3 mb-2">Nenhum produto encontrado</h4>
                <p class="text-rh-muted fs-sm mb-4">
                    @if(request('busca'))
                    Nenhum resultado para "{{ request('busca') }}". Que tal cadastrá-lo?
                    @else
                    Ainda não há produtos nesta categoria.
                    @endif
                </p>
                <a href="{{ route('produtos.create') }}" class="btn-rh-primary">
                    <i class="bi bi-plus-lg"></i> Cadastrar Produto
                </a>
            </div>
            @endforelse

            <!-- Paginação -->
            @if($produtos->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                <nav>
                    <ul class="pagination rh-pagination">
                        {{-- Previous --}}
                        <li class="page-item {{ $produtos->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $produtos->previousPageUrl() }}">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>

                        @foreach($produtos->getUrlRange(1, $produtos->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $produtos->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        {{-- Next --}}
                        <li class="page-item {{ !$produtos->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $produtos->nextPageUrl() }}">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection