{{--
    CONCEITO: Blade Component com slot
    Uso: <x-produto-card :produto="$produto" />
--}}

@props(['produto'])

<a href="{{ route('produtos.show', $produto) }}"
   class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col">

    {{-- Imagem --}}
    <div class="aspect-square overflow-hidden bg-gray-100">
        <img
            src="{{ $produto->imagem_url }}"
            alt="{{ $produto->nome }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            onerror="this.src='https://placehold.co/400x400/f3f4f6/9ca3af?text=Sem+Imagem'"
        >
    </div>

    {{-- Conteúdo --}}
    <div class="p-4 flex flex-col flex-1">
        {{-- Categoria --}}
        <span class="text-xs text-indigo-600 font-medium mb-1">
            {{ $produto->categoria->icone }} {{ $produto->categoria->nome }}
        </span>

        {{-- Nome --}}
        <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm leading-snug mb-1 group-hover:text-indigo-600 transition">
            {{ $produto->nome }}
        </h3>

        @if($produto->marca)
            <p class="text-xs text-gray-400 mb-2">{{ $produto->marca }}</p>
        @endif

        <div class="mt-auto">
            {{-- Estrelas --}}
            <div class="flex items-center gap-2">
                <x-estrelas :nota="$produto->media_nota" tamanho="sm" />
                <span class="text-xs text-gray-500">
                    @if($produto->total_avaliacoes > 0)
                        {{ number_format($produto->media_nota, 1) }}
                        ({{ $produto->total_avaliacoes }}
                        {{ Str::plural('avaliação', $produto->total_avaliacoes) }})
                    @else
                        Sem avaliações
                    @endif
                </span>
            </div>
        </div>
    </div>
</a>
