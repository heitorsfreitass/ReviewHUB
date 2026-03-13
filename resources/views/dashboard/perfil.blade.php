@extends('layouts.app')
@section('title', 'Meu Perfil')

@section('content')
<div class="container py-4">
    <div class="row g-4">

        <!-- Coluna esquerda: dados do usuário -->
        <div class="col-lg-4">

            <!-- Card perfil -->
            <div class="rh-card p-4 text-center mb-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;" class="mb-3">
                <h2 class="h5 fw-700 mb-0">{{ $user->name }}</h2>
                <p class="text-rh-muted fs-sm mb-3">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-4">
                    <div class="text-center">
                        <div class="fw-700">{{ $user->produtos()->count() }}</div>
                        <div class="fs-xs text-rh-muted">Produtos</div>
                    </div>
                    <div style="border-left:1px solid var(--rh-border);"></div>
                    <div class="text-center">
                        <div class="fw-700">{{ $user->avaliacoes()->count() }}</div>
                        <div class="fs-xs text-rh-muted">Avaliações</div>
                    </div>
                </div>
            </div>

            <!-- Editar dados -->
            <div class="rh-card p-4">
                <h3 class="h6 fw-700 mb-3">
                    <i class="bi bi-pencil text-rh-primary me-1"></i> Editar dados
                </h3>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="rh-form-label">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="rh-form-control {{ $errors->has('name') ? 'is-invalid' : '' }}">
                        @error('name') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="rh-form-label">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="rh-form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">
                        @error('email') <div class="rh-invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn-rh-primary w-100 justify-content-center">
                        <i class="bi bi-check-lg"></i> Salvar
                    </button>
                </form>
            </div>
        </div>

        <!-- Coluna direita: histórico -->
        <div class="col-lg-8">

            <!-- Minhas avaliações -->
            <div class="rh-card p-4 mb-4">
                <h3 class="h6 fw-700 mb-3">
                    <i class="bi bi-star-fill text-rh-accent me-1"></i> Minhas avaliações
                </h3>

                @forelse($avaliacoes as $av)
                <div class="d-flex align-items-start gap-3 py-3 border-bottom">
                    <img src="{{ $av->produto->imagem_url }}" alt="{{ $av->produto->nome }}"
                        style="width:48px;height:48px;object-fit:cover;border-radius:var(--rh-radius);flex-shrink:0;">
                    <div class="flex-fill min-w-0">
                        <a href="{{ route('produtos.show', $av->produto) }}"
                            class="fw-600 fs-sm d-block text-truncate">
                            {{ $av->produto->nome }}
                        </a>
                        <div class="d-flex align-items-center gap-2 mt-0.5">
                            <x-estrelas :nota="$av->nota" tamanho="sm" />
                            <span class="fs-xs text-rh-muted">{{ $av->titulo }}</span>
                        </div>
                        <div class="fs-xs text-rh-muted">{{ $av->created_at->diffForHumans() }}</div>
                    </div>
                    <a href="{{ route('produtos.avaliacoes.edit', [$av->produto, $av]) }}"
                        class="fs-xs text-rh-primary flex-shrink-0">Editar</a>
                </div>
                @empty
                <p class="text-rh-muted fs-sm mb-0">Você ainda não escreveu nenhuma avaliação.</p>
                @endforelse

                <div class="mt-3">{{ $avaliacoes->links() }}</div>
            </div>

            <!-- Meus produtos -->
            <div class="rh-card p-4">
                <h3 class="h6 fw-700 mb-3">
                    <i class="bi bi-box-seam text-rh-primary me-1"></i> Produtos que cadastrei
                </h3>

                @forelse($produtos as $p)
                <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                    <img src="{{ $p->imagem_url }}" alt="{{ $p->nome }}"
                        style="width:42px;height:42px;object-fit:cover;border-radius:8px;flex-shrink:0;">
                    <div class="flex-fill min-w-0">
                        <a href="{{ route('produtos.show', $p) }}"
                            class="fw-600 fs-sm d-block text-truncate">{{ $p->nome }}</a>
                        <div class="fs-xs text-rh-muted">
                            {{ $p->categoria->nome }} &middot; {{ $p->total_avaliacoes }} avaliação(ões)
                        </div>
                    </div>
                    <a href="{{ route('produtos.edit', $p) }}" class="fs-xs text-rh-primary flex-shrink-0">Editar</a>
                </div>
                @empty
                <p class="text-rh-muted fs-sm mb-0">Você ainda não cadastrou nenhum produto.</p>
                @endforelse

                <div class="mt-3">{{ $produtos->links() }}</div>
            </div>

        </div>
    </div>
</div>
@endsection