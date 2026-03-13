<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ReviewHub') — ReviewHub</title>

    {{--
        CONCEITO: @yield e @section
        Layout principal define "slots" com @yield.
        Views filhas preenchem esses slots com @section/@endsection.
    --}}

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .star-filled  { color: #f59e0b; }
        .star-empty   { color: #d1d5db; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl text-indigo-600">
                    <span class="text-2xl">⭐</span> ReviewHub
                </a>

                {{-- Busca central --}}
                <form action="{{ route('produtos.index') }}" method="GET" class="hidden md:flex flex-1 max-w-lg mx-8">
                    <div class="relative w-full">
                        <input
                            type="text"
                            name="busca"
                            value="{{ request('busca') }}"
                            placeholder="Buscar produto..."
                            class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        >
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600">
                            🔍
                        </button>
                    </div>
                </form>

                {{-- Nav direita --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('produtos.create') }}"
                       class="hidden sm:inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-full transition">
                        + Cadastrar Produto
                    </a>

                    {{--
                        CONCEITO: Diretivas de autenticação Blade
                        @auth   → bloco exibido apenas para logados
                        @guest  → bloco exibido apenas para visitantes
                    --}}
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm hover:text-indigo-600">
                                <img src="{{ auth()->user()->avatar_url }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full object-cover">
                                <span class="hidden md:block font-medium">{{ auth()->user()->name }}</span>
                                <span class="text-xs">▼</span>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                 class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">👤 Meu Perfil</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        🚪 Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Entrar</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-full transition">Cadastrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- FLASH MESSAGES --}}
    {{--
        CONCEITO: session() e flash messages
        O Controller faz: redirect()->with('sucesso', 'Mensagem')
        O Blade lê com: session('sucesso')
    --}}
    @if(session('sucesso'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 flex items-center gap-2">
                ✅ {{ session('sucesso') }}
            </div>
        </div>
    @endif

    @if(session('erro'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 flex items-center gap-2">
                ❌ {{ session('erro') }}
            </div>
        </div>
    @endif

    {{-- CONTEÚDO PRINCIPAL --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-sm text-gray-500">
            <p>⭐ <strong class="text-gray-700">ReviewHub</strong> — Reviews honestas de produtos reais</p>
            <p class="mt-1">{{ \App\Models\Produto::count() }} produtos · {{ \App\Models\Avaliacao::count() }} avaliações</p>
        </div>
    </footer>

    {{-- Alpine.js para o dropdown --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
