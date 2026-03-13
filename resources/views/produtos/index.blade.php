@extends('layouts.app')

@section('title', 'Produtos')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">

    <div class="flex flex-col md:flex-row gap-8">

        {{-- SIDEBAR DE FILTROS --}}
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 sticky top-20">
                <h3 class="font-semibold mb-4">🔎 Filtros</h3>

                <form action="{{ route('produtos.index') }}" method="GET" id="form-filtros">

                    {{-- Busca --}}
                    <div class="mb-5">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">
                            Buscar
                        </label>
                        <input type="text" name="busca" value="{{ request('busca') }}"
                               placeholder="Nome, marca..."
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    {{-- Categorias --}}
                    <div class="mb-5">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">
                            Categoria
                        </label>
                        <div class="space-y-1 max-h-60 overflow-y-auto">
                            {{--
                                CONCEITO: request()->is() e request('param')
                                request('categoria') lê o ?categoria= da URL atual
                                Comparamos para marcar o item ativo
                            --}}
                            <a href="{{ route('produtos.index', array_merge(request()->except('categoria', 'page'), [])) }}"
                               class="flex items-center justify-between px-3 py-1.5 rounded-lg text-sm transition
                                      {{ !request('categoria') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>Todas</span>
                            </a>
                            @foreach($categorias as $cat)
                                <a href="{{ route('produtos.index', array_merge(request()->except('categoria', 'page'), ['categoria' => $cat->slug])) }}"
                                   class="flex items-center justify-between px-3 py-1.5 rounded-lg text-sm transition
                                          {{ request('categoria') === $cat->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span>{{ $cat->icone }} {{ $cat->nome }}</span>
                                    <span class="text-xs text-gray-400">{{ $cat->produtos_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Ordenação --}}
                    <div class="mb-5">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2 block">
                            Ordenar por
                        </label>
                        <select name="ordem" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="recentes"       {{ request('ordem', 'recentes') === 'recentes'       ? 'selected' : '' }}>Mais recentes</option>
                            <option value="mais_avaliados" {{ request('ordem') === 'mais_avaliados' ? 'selected' : '' }}>Mais avaliados</option>
                            <option value="melhor_nota"    {{ request('ordem') === 'melhor_nota'    ? 'selected' : '' }}>Melhor nota</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg text-sm font-medium transition">
                        Aplicar filtros
                    </button>

                    @if(request()->hasAny(['busca', 'categoria', 'ordem']))
                        <a href="{{ route('produtos.index') }}"
                           class="block text-center mt-2 text-sm text-gray-400 hover:text-red-500">
                            Limpar filtros
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        {{-- GRID DE PRODUTOS --}}
        <div class="flex-1">

            {{-- Cabeçalho dos resultados --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        @if(request('busca'))
                            Resultados para "{{ request('busca') }}"
                        @elseif(request('categoria'))
                            {{ $categorias->firstWhere('slug', request('categoria'))?->icone }}
                            {{ $categorias->firstWhere('slug', request('categoria'))?->nome }}
                        @else
                            Todos os produtos
                        @endif
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $produtos->total() }} {{ Str::plural('produto', $produtos->total()) }} encontrado{{ $produtos->total() !== 1 ? 's' : '' }}
                    </p>
                </div>
                <a href="{{ route('produtos.create') }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-full text-sm font-medium transition">
                    + Cadastrar
                </a>
            </div>

            {{--
                CONCEITO: @forelse
                Como @foreach, mas com @empty para quando a coleção estiver vazia.
                Muito mais limpo que um @if($collection->isEmpty()) separado.
            --}}
            @forelse($produtos as $produto)
                @if($loop->first)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @endif

                <x-produto-card :produto="$produto" />

                @if($loop->last)
                    </div>
                @endif
            @empty
                <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                    <p class="text-5xl mb-4">🔍</p>
                    <h3 class="text-lg font-semibold mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-500 text-sm mb-6">
                        @if(request('busca'))
                            Nenhum resultado para "{{ request('busca') }}". Que tal cadastrá-lo?
                        @else
                            Ainda não há produtos nesta categoria.
                        @endif
                    </p>
                    <a href="{{ route('produtos.create') }}"
                       class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-indigo-700 transition">
                        + Cadastrar Produto
                    </a>
                </div>
            @endforelse

            {{--
                CONCEITO: Paginação
                $produtos->links() renderiza os botões de página.
                O Laravel gera automaticamente URLs com ?page=N.
                withQueryString() mantém os outros filtros na URL.
            --}}
            @if($produtos->hasPages())
                <div class="mt-8">
                    {{ $produtos->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
