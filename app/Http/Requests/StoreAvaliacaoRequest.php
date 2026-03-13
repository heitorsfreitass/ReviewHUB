<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvaliacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $produtoId = $this->route('produto')->id;

        return [
            'nota'       => ['required', 'integer', 'min:1', 'max:5'],
            'titulo'     => ['required', 'string', 'min:5', 'max:120'],
            'conteudo'   => ['required', 'string', 'min:20', 'max:5000'],
            'preco_pago' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'loja'       => ['nullable', 'string', 'max:120'],
            'url_loja'   => ['nullable', 'url', 'max:500'],
            'recomenda'  => ['required', 'boolean'],

            // 1 review por usuário por produto
            // unique com where()
            'produto_id' => [
                Rule::unique('avaliacoes', 'produto_id')
                    ->where('user_id', auth()->id())
                    ->ignore($this->route('avaliacao')?->id),
            ],
            'images'     => ['nullable', 'array', 'max:5'],
            'imagens.*'  => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nota.required'      => 'Dê uma nota de 1 a 5 estrelas.',
            'nota.min'           => 'A nota mínima é 1 estrela.',
            'nota.max'           => 'A nota máxima é 5 estrelas.',
            'titulo.required'    => 'Dê um título para sua avaliação.',
            'titulo.min'         => 'O título deve ter pelo menos 5 caracteres.',
            'conteudo.required'  => 'Escreva o conteúdo da sua avaliação.',
            'conteudo.min'       => 'A avaliação precisa ter pelo menos 20 caracteres.',
            'url_loja.url'       => 'Informe uma URL válida para a loja.',
            'produto_id.unique'  => 'Você já avaliou este produto.',
            'imagens.max'        => 'Máximo de 5 imagens por avaliaão.',
            'imagens.*.image'    => 'Todos os arquivos devem ser imagens',
            'imagens.*.mimes'    => 'Formatos aceitos: JPG, PNG, WEBP.',
            'imagens.*.max'      => 'Cada imagem pode ter no máximo 2MB.'
        ];
    }

    /** Injeta produto_id automaticamente no request */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'produto_id' => $this->route('produto')->id,
            'recomenda'  => $this->boolean('recomenda'),
        ]);
    }
}
