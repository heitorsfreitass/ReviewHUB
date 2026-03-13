{{--
    CONCEITO: @extends e @section
    Esta view "estende" o layout principal.
    @section('content') preenche o @yield('content') do layout.
--}}
@extends('layouts.app')

@section('title', 'Início')

@section('content')

{{-- HERO --}}
<section class="bg-gradient-to-br from-indigo-600 to-purple-700 text-white py-16 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Reviews reais de produtos reais
        </h1>
        <p class="text-indigo-100 text-lg mb-8 max-w-2xl mx-auto">
            Antes de comprar, descubra o que outras pessoas acharam.
            Avaliações honestas com preço pago, loja e muito mais.
        </p>

        {{-- Barra de busca --}}
        <form action="{{ route('produtos.index') }}" method="GET" class="max-w-xl mx-auto">
            <div class="flex gap-2 bg-white rounded-full p-1.5 shadow-xl">
                <input
                    type="text"
                    name="busca"
                    placeholder="Ex: iPhone 15, tênis Nike, cafeteira..."
                    class="flex-1 px-4 py-2 text-gray-900 text-sm rounded-full focus:outline-none"
                >
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-full text-sm font-medium transition">
                    Buscar
                </button>
            </div>
        </form>

        {{-- Estatísticas --}}
        <div class="flex justify-center gap-8 mt-10 text-sm">
            <div>
                <p class="text-2xl font-bold">{{ number_format($totalProdutos) }}</p>
                <p class="text-indigo-200">Produtos</p>
            </div>
            <div class="border-l border-indigo-400"></div>
            <div>
                <p class="text-2xl font-bold">{{ number_format($totalAvaliacoes) }}</p>
                <p class="text-indigo-200">Avaliações</p>
            </div>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12 space-y-16">

    {{-- Categorias --}}
    <section>
        <h2 class="text-xl font-bold mb-6">📂 Explorar por categoria</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-10 gap-3">
            {{--
                CONCEITO: @foreach
                Itera sobre coleções Eloquent ou arrays.
                $loop->first, $loop->last, $loop->index disponíveis dentro do loop.
            --}}
            @foreach($categorias as $categoria)
                <a href="{{ route('produtos.index', ['categoria' => $categoria->slug]) }}"
                   class="flex flex-col items-center gap-1 p-3 bg-white rounded-xl border border-gray-100 hover:border-indigo-300 hover:shadow-sm transition text-center group">
                    <span class="text-2xl">{{ $categoria->icone }}</span>
                    <span class="text-xs font-medium text-gray-700 group-hover:text-indigo-600 leading-tight">
                        {{ $categoria->nome }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $categoria->produtos_count }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Melhor Avaliados --}}
    @if($melhorNotados->isNotEmpty())
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">⭐ Melhor avaliados</h2>
            <a href="{{ route('produtos.index', ['ordem' => 'melhor_nota']) }}"
               class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            {{-- CONCEITO: Blade Component <x-nome-do-componente> --}}
            @foreach($melhorNotados as $produto)
                <x-produto-card :produto="$produto" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- Mais Avaliados --}}
    @if($maisAvaliados->isNotEmpty())
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">🔥 Mais avaliados</h2>
            <a href="{{ route('produtos.index', ['ordem' => 'mais_avaliados']) }}"
               class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($maisAvaliados as $produto)
                <x-produto-card :produto="$produto" />
            @endforeach
        </div>
    </section>
    @endif

    {{-- Recém adicionados + CTA --}}
    <section class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold">🆕 Recém adicionados</h2>
                <a href="{{ route('produtos.index') }}" class="text-sm text-indigo-600 hover:underline">Ver todos →</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach($recemAdicionados as $produto)
                    <x-produto-card :produto="$produto" />
                @endforeach
            </div>
        </div>

        {{-- CTA Cadastrar produto --}}
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl p-8 flex flex-col justify-center items-center text-center">
            <span class="text-5xl mb-4">📦</span>
            <h3 class="text-lg font-bold mb-2">Não achou o produto?</h3>
            <p class="text-gray-600 text-sm mb-6">
                Cadastre-o e seja o primeiro a avaliar. Ajude outras pessoas a decidirem!
            </p>
            <a href="{{ route('produtos.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full font-medium text-sm transition w-full text-center">
                + Cadastrar Produto
            </a>
            @guest
                <p class="text-xs text-gray-400 mt-3">É necessário ter uma conta.</p>
            @endguest
        </div>
    </section>

</div>
@endsection
