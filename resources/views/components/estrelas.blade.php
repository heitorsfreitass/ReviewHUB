{{--
    CONCEITO: Blade Components (components anônimos)
    -------------------------------------------------
    Arquivo em resources/views/components/estrelas.blade.php
    Uso no Blade: <x-estrelas :nota="$produto->media_nota" tamanho="lg" />

    $nota    → número de 0 a 5 (aceita decimais como 4.5)
    $tamanho → 'sm', 'md', 'lg'

    O $attributes->merge() repassa classes extras do componente pai.
--}}

@props([
    'nota'    => 0,
    'tamanho' => 'md',
    'mostrarNumero' => false,
])

@php
    $tamanhos = [
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-xl',
    ];
    $classe = $tamanhos[$tamanho] ?? 'text-base';
    $notaFloat = (float) $nota;
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-0.5 $classe"]) }}>
    @for($i = 1; $i <= 5; $i++)
        @if($notaFloat >= $i)
            {{-- Estrela cheia --}}
            <span class="star-filled">★</span>
        @elseif($notaFloat >= $i - 0.5)
            {{-- Meia estrela (via clip) --}}
            <span class="relative inline-block">
                <span class="star-empty">★</span>
                <span class="star-filled absolute inset-0 overflow-hidden" style="width:50%">★</span>
            </span>
        @else
            {{-- Estrela vazia --}}
            <span class="star-empty">★</span>
        @endif
    @endfor

    @if($mostrarNumero && $notaFloat > 0)
        <span class="ml-1 text-gray-600 font-medium">{{ number_format($notaFloat, 1) }}</span>
    @endif
</span>
