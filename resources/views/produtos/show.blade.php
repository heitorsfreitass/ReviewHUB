@extends('layouts.app')

@section('title', $produto->nome)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-400 mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Início</a>
        <span>/</span>
        <a href="{{ route('produtos.index') }}" class="hover:text-indigo-600">Produtos</a>
        <span>/</span>
        <a href="{{ route('produtos.index', ['categoria' => $produto->categoria->slug]) }}" class="hover:text-indigo-600">
            {{ $produto->categoria->nome }}
        </a>
        <span>/</span>
        <span class="text-gray-700 truncate max-w-xs">{{ $produto->nome }}</span>
    </nav>

    {{-- PRODUTO: Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="md:flex">

            {{-- Imagem --}}
            <div class="md:w-80 flex-shrink-0">
                <img src="{{ $produto->imagem_url }}"
                    alt="{{ $produto->nome }}"
                    class="w-full h-72 md:h-full object-cover"
                    onerror="this.src='https://placehold.co/600x400/f3f4f6/9ca3af?text=Sem+Imagem'">
            </div>

            {{-- Detalhes --}}
            <div class="p-8 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">
                            {{ $produto->categoria->icone }} {{ $produto->categoria->nome }}
                        </span>
                        <h1 class="text-2xl font-bold mt-3 leading-snug">{{ $produto->nome }}</h1>
                        @if($produto->marca)
                        <p class="text-gray-500 mt-1">por <strong>{{ $produto->marca }}</strong></p>
                        @endif
                    </div>

                    {{--
                        CONCEITO: @can — diretiva de autorização no Blade
                        Equivale ao $this->authorize() no Controller.
                        Só exibe se o usuário tem permissão conforme a Policy.
                    --}}
                    @can('update', $produto)
                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('produtos.edit', $produto) }}"
                            class="text-sm border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-lg transition">
                            ✏️ Editar
                        </a>
                        <form action="{{ route('produtos.destroy', $produto) }}" method="POST"
                            onsubmit="return confirm('Tem certeza? As avaliações também serão removidas.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-sm border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                🗑️
                            </button>
                        </form>
                    </div>
                    @endcan
                </div>

                @if($produto->descricao)
                <p class="text-gray-600 text-sm mt-4 leading-relaxed">{{ $produto->descricao }}</p>
                @endif

                {{-- Resumo de notas --}}
                <div class="mt-6 flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-5xl font-bold text-gray-900">
                            {{ $produto->total_avaliacoes > 0 ? number_format($produto->media_nota, 1) : '—' }}
                        </p>
                        <x-estrelas :nota="$produto->media_nota" tamanho="lg" class="mt-1" />
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $produto->total_avaliacoes }} {{ Str::plural('avaliação', $produto->total_avaliacoes) }}
                        </p>
                    </div>

                    {{-- Barra de distribuição --}}
                    @if($produto->total_avaliacoes > 0)
                    <div class="flex-1 space-y-1">
                        @foreach([5,4,3,2,1] as $nota)
                        @php $qtd = $distribuicaoNotas->get($nota, 0); @endphp
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-500 w-3">{{ $nota }}</span>
                            <span class="star-filled text-xs">★</span>
                            <div class="flex-1 bg-gray-100 rounded-full h-2">
                                <div class="bg-amber-400 h-2 rounded-full transition-all"
                                    style="width: {{ $produto->total_avaliacoes > 0 ? ($qtd / $produto->total_avaliacoes) * 100 : 0 }}%">
                                </div>
                            </div>
                            <span class="text-gray-400 w-4">{{ $qtd }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Botão de avaliar --}}
                <div class="mt-6">
                    @auth
                    @if($jaAvaliou)
                    <p class="text-sm text-gray-400 flex items-center gap-2">
                        ✅ Você já avaliou este produto
                        {{-- Link para editar a avaliação --}}
                        @php
                        $minhaAvaliacao = $produto->avaliacoes->firstWhere('user_id', auth()->id());
                        @endphp
                        @if($minhaAvaliacao)
                        · <a href="{{ route('produtos.avaliacoes.edit', [$produto, $minhaAvaliacao]) }}"
                            class="text-indigo-600 hover:underline">Editar</a>
                        @endif
                    </p>
                    @else
                    <a href="{{ route('produtos.avaliacoes.create', $produto) }}"
                        class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-white font-medium px-6 py-3 rounded-xl transition">
                        ⭐ Escrever avaliação
                    </a>
                    @endif
                    @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-2 bg-amber-400 hover:bg-amber-500 text-white font-medium px-6 py-3 rounded-xl transition">
                        ⭐ Entrar para avaliar
                    </a>
                    @endauth
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    Cadastrado por <strong>{{ $produto->user->name }}</strong>
                    {{ $produto->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    {{-- SEÇÃO DE AVALIAÇÕES --}}
    <div>
        <h2 class="text-xl font-bold mb-6">
            Avaliações ({{ $produto->total_avaliacoes }})
        </h2>

        @forelse($produto->avaliacoes as $avaliacao)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4" id="avaliacao-{{ $avaliacao->id }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $avaliacao->user->avatar_url }}" alt="{{ $avaliacao->user->name }}"
                        class="w-10 h-10 rounded-full object-cover">
                    <div>
                        <p class="font-semibold text-sm">{{ $avaliacao->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $avaliacao->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @can('update', $avaliacao)
                <div class="flex gap-2">
                    <a href="{{ route('produtos.avaliacoes.edit', [$produto, $avaliacao]) }}"
                        class="text-xs border border-gray-200 hover:bg-gray-50 px-2.5 py-1 rounded-lg">✏️</a>
                    <form action="{{ route('produtos.avaliacoes.destroy', [$produto, $avaliacao]) }}"
                        method="POST" onsubmit="return confirm('Remover avaliação?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs border border-red-200 text-red-500 hover:bg-red-50 px-2.5 py-1 rounded-lg">🗑️</button>
                    </form>
                </div>
                @endcan
            </div>

            {{-- Nota e título --}}
            <div class="mt-4">
                <div class="flex items-center gap-3 flex-wrap">
                    <x-estrelas :nota="$avaliacao->nota" tamanho="md" />
                    <span class="font-semibold text-sm">{{ $avaliacao->titulo }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full
                                     {{ $avaliacao->recomenda ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                        {{ $avaliacao->recomenda ? '👍 Recomenda' : '👎 Não recomenda' }}
                    </span>
                </div>
            </div>

            {{-- Detalhes de compra --}}
            @if($avaliacao->preco_pago || $avaliacao->loja)
            <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-500">
                @if($avaliacao->preco_pago)
                <span class="flex items-center gap-1">
                    💰 Pagou <strong class="text-gray-700">{{ $avaliacao->preco_pago_formatado }}</strong>
                </span>
                @endif
                @if($avaliacao->loja)
                <span class="flex items-center gap-1">
                    🏪
                    @if($avaliacao->url_loja)
                    <a href="{{ $avaliacao->url_loja }}" target="_blank" rel="noopener"
                        class="text-indigo-600 hover:underline font-medium">
                        {{ $avaliacao->loja }} ↗
                    </a>
                    @else
                    <strong class="text-gray-700">{{ $avaliacao->loja }}</strong>
                    @endif
                </span>
                @endif
            </div>
            @endif

            {{-- Conteúdo --}}
            <p class="text-gray-700 text-sm mt-4 leading-relaxed">{{ $avaliacao->conteudo }}</p>

            {{-- Botão "útil" --}}
            <div class="mt-4 flex items-center gap-3">
                <span class="text-xs text-gray-400">Essa avaliação foi útil?</span>
                @auth
                <form action="{{ route('avaliacoes.util', $avaliacao) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-1 text-xs px-3 py-1.5 rounded-full border transition
                                           {{ in_array($avaliacao->id, $votosDoUsuario)
                                              ? 'bg-indigo-50 border-indigo-300 text-indigo-700 font-medium'
                                              : 'border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600' }}">
                        👍 Sim ({{ $avaliacao->votos_uteis }})
                    </button>
                </form>
                @else
                <span class="text-xs border border-gray-200 px-3 py-1.5 rounded-full text-gray-400">
                    👍 {{ $avaliacao->votos_uteis }} úteis
                </span>
                @endauth
            </div>

            {{-- Imagens da avaliação --}}
            @if($avaliacao->imagens_urls)
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($avaliacao->imagens_urls as $url)
                <a href="{{ $url }}" target="_blank">
                    <img src="{{ $url }}" alt="Foto da avaliação"
                        class="w-24 h-24 object-cover rounded-xl border border-gray-200
                            hover:opacity-90 hover:scale-105 transition-transform cursor-zoom-in">
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
            <p class="text-4xl mb-3">✍️</p>
            <h3 class="font-semibold mb-2">Seja o primeiro a avaliar!</h3>
            <p class="text-sm text-gray-500 mb-6">
                Este produto ainda não tem avaliações. Sua opinião ajuda outras pessoas.
            </p>
            @auth
            <a href="{{ route('produtos.avaliacoes.create', $produto) }}"
                class="inline-block bg-amber-400 hover:bg-amber-500 text-white px-6 py-2 rounded-full text-sm font-medium transition">
                ⭐ Avaliar agora
            </a>
            @else
            <a href="{{ route('login') }}"
                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full text-sm font-medium transition">
                Entrar para avaliar
            </a>
            @endauth
        </div>
        @endforelse
    </div>
</div>
@endsection