<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ReviewHub') — ReviewHub</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS próprio -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="rh-navbar navbar navbar-expand-lg">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-star-fill"></i> ReviewHub
        </a>

        <!-- Toggle mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <!-- Busca central -->
            <form action="{{ route('produtos.index') }}" method="GET" class="mx-auto d-flex" style="width: 100%; max-width: 420px;">
                <div class="input-group">
                    <input type="text" name="busca" value="{{ request('busca') }}"
                           class="rh-search-input form-control border-end-0"
                           placeholder="Buscar produto...">
                    <button type="submit" class="btn border border-start-0" style="border-color: var(--rh-border); background:var(--rh-bg); border-left: none;">
                        <i class="bi bi-search text-rh-muted"></i>
                    </button>
                </div>
            </form>

            <!-- Nav direita -->
            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="btn-rh-primary" href="{{ route('produtos.create') }}">
                        <i class="bi bi-plus-lg"></i> Cadastrar Produto
                    </a>
                </li>

                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-0"
                           href="#" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->name }}"
                                 style="width:34px;height:34px;border-radius:50%;object-fit:cover;">
                            <span class="d-none d-lg-inline fw-600 fs-sm">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: var(--rh-radius);">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> Meu Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-rh-ghost" href="{{ route('register') }}">Cadastrar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Flash messages -->
<div class="container mt-3">
    @if(session('sucesso'))
        <div class="rh-alert rh-alert-success">
            <i class="bi bi-check-circle-fill"></i> {{ session('sucesso') }}
        </div>
    @endif
    @if(session('erro'))
        <div class="rh-alert rh-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i> {{ session('erro') }}
        </div>
    @endif
</div>

<!-- CONTEÚDO -->
<main>
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="rh-footer">
    <div class="container">
        <p class="mb-1">
            <i class="bi bi-star-fill text-rh-accent"></i>
            <strong>ReviewHub</strong> — Reviews honestas de produtos reais
        </p>
        <p class="mb-0 fs-xs">
            {{ \App\Models\Produto::count() }} produtos &middot; {{ \App\Models\Avaliacao::count() }} avaliações
        </p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
