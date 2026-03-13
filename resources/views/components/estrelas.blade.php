@props([
    'nota'          => 0,
    'tamanho'       => 'md',
    'mostrarNumero' => false,
])

@php
    $classes = [
        'sm' => 'rh-stars rh-stars-sm',
        'md' => 'rh-stars rh-stars-md',
        'lg' => 'rh-stars rh-stars-lg',
        'xl' => 'rh-stars rh-stars-xl',
    ];
    $cls = $classes[$tamanho] ?? 'rh-stars rh-stars-md';
    $n   = (float) $nota;
@endphp

<span {{ $attributes->merge(['class' => $cls]) }}>
    @for($i = 1; $i <= 5; $i++)
        @if($n >= $i)
            <span class="star-on"><i class="bi bi-star-fill"></i></span>
        @elseif($n >= $i - 0.5)
            <span class="star-on"><i class="bi bi-star-half"></i></span>
        @else
            <span class="star-off"><i class="bi bi-star"></i></span>
        @endif
    @endfor

    @if($mostrarNumero && $n > 0)
        <span class="ms-1 text-rh-muted fw-600" style="font-size:.9em">
            {{ number_format($n, 1) }}
        </span>
    @endif
</span>
