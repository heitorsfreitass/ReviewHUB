@props(['produto'])

<a href="{{ route('produtos.show', $produto) }}" class="rh-card rh-product-card">
    <div class="rh-product-img-wrapper">
        <img src="{{ $produto->imagem_url }}"
            alt="{{ $produto->nome }}"
            class="rh-product-img"
            onerror="this.src='https://placehold.co/400x400/f3f4f6/9ca3af?text=Sem+Foto'">
    </div>
    <div class="rh-product-body">
        <div class="rh-product-category">
            {{ $produto->categoria->icone }} {{ $produto->categoria->nome }}
        </div>
        <div class="rh-product-title">{{ $produto->nome }}</div>
        @if($produto->marca)
        <div class="rh-product-brand">{{ $produto->marca }}</div>
        @endif
        <div class="rh-product-footer">
            <x-estrelas :nota="$produto->media_nota" tamanho="sm" />
            <span class="fs-xs text-rh-muted ms-1">
                @if($produto->total_avaliacoes > 0)
                {{ number_format($produto->media_nota, 1) }}
                ({{ $produto->total_avaliacoes }})
                @else
                Sem avaliações
                @endif
            </span>
        </div>
    </div>
</a>