<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * O Controller fica limpo — só recebe $request já validado.
 * authorize() → true se o usuário pode fazer a ação
 * rules()     → Laravel Validator
 * messages()  → mensagens de erro
 * attributes()→ nomes dos campos nos erros
 */
class StoreProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check(); // só autenticados podem cadastrar
    }

    public function rules(): array
    {
        return [
            'nome'         => ['required', 'string', 'min:2', 'max:200'],
            'marca'        => ['nullable', 'string', 'max:100'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'descricao'    => ['nullable', 'string', 'max:1000'],
            'imagem'       => [
                'required',
                'image',                  // deve ser imagem (jpg, png, gif, webp)
                'mimes:jpg,jpeg,png,webp',
                'max:4096',               // máximo 4MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'         => 'O nome do produto é obrigatório.',
            'nome.min'              => 'O nome deve ter pelo menos 2 caracteres.',
            'nome.max'              => 'O nome não pode passar de 200 caracteres.',
            'categoria_id.required' => 'Selecione uma categoria.',
            'categoria_id.exists'   => 'Categoria inválida.',
            'imagem.required'       => 'A imagem do produto é obrigatória.',
            'imagem.image'          => 'O arquivo deve ser uma imagem.',
            'imagem.mimes'          => 'Formatos aceitos: JPG, PNG, WEBP.',
            'imagem.max'            => 'A imagem não pode passar de 4MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nome'         => 'nome do produto',
            'categoria_id' => 'categoria',
            'imagem'       => 'imagem',
        ];
    }
}
