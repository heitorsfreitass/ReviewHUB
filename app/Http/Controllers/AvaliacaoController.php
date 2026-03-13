<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAvaliacaoRequest;
use App\Models\Avaliacao;
use App\Models\Produto;
use App\Models\VotoUtilidade;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller
{
    /** Formulário para criar avaliação */
    public function create(Produto $produto)
    {
        // Se já avaliou, redireciona para a página do produto
        $jaAvaliou = $produto->avaliacoes()
                             ->where('user_id', auth()->id())
                             ->exists();

        if ($jaAvaliou) {
            return redirect()
                ->route('produtos.show', $produto)
                ->with('erro', 'Você já avaliou este produto.');
        }

        return view('avaliacoes.create', compact('produto'));
    }

    /** Salva nova avaliação */
    public function store(StoreAvaliacaoRequest $request, Produto $produto)
    {   
        $dados = $request->validated();

        if ($request->hasFile('imagens')) {
            $dados['imagens'] = collect($request->file('imagens'))
                ->map(fn($img) => $img->store('avaliacoes', 'public'))
                ->toArray();
        }

        $produto->avaliacoes()->create([
            ...$dados,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('produtos.show', $produto)
            ->with('sucesso', 'Avaliação publicada! Obrigado por contribuir.');
    }

    /** Formulário de edição */
    public function edit(Produto $produto, Avaliacao $avaliacao)
    {
        $this->authorize('update', $avaliacao);
        return view('avaliacoes.edit', compact('produto', 'avaliacao'));
    }

    /** Atualiza avaliação */
    public function update(StoreAvaliacaoRequest $request, Produto $produto, Avaliacao $avaliacao)
    {
        $this->authorize('update', $avaliacao);

        $dados = $request->validated();

        if ($request->hasFile('imagens')) {
            foreach ($avaliacao->imagens ?? [] as $path) {
                Storage::disk('public')->delete($path);
            }

            $dados['imagens'] = collect($request->file('imagens'))
                ->map(fn($img) => $img->store('avaliacoes', 'public'))
                ->toArray();
        } else {
            unset($dados['imagens']); // deixa as que já tem
        }

        $avaliacao->update($dados);

        return redirect()
            ->route('produtos.show', $produto)
            ->with('sucesso', 'Avaliação atualizada!');
    }

    /** Remove avaliação */
    public function destroy(Produto $produto, Avaliacao $avaliacao)
    {
        $this->authorize('delete', $avaliacao);

        $avaliacao->delete();

        return redirect()
            ->route('produtos.show', $produto)
            ->with('sucesso', 'Avaliação removida.');
    }

    /**
     * CONCEITO: Rota de ação customizada (não-CRUD)
     * Toggle "útil" — marca/desmarca avaliação como útil
     */
    public function toggleUtil(Avaliacao $avaliacao)
    {
        $userId = auth()->id();

        /**
         * CONCEITO: firstOrCreate
         * Busca o registro; se não existir, cria.
         * Retorna [Model, bool $foiCriado]
         */
        [$voto, $criado] = VotoUtilidade::firstOrCreate([
            'avaliacao_id' => $avaliacao->id,
            'user_id'      => $userId,
        ])->only(['avaliacao_id', 'user_id']) + ['_criado' => false];

        // Se já existia, remove (toggle)
        $votoExistente = VotoUtilidade::where('avaliacao_id', $avaliacao->id)
                                      ->where('user_id', $userId)
                                      ->first();

        if ($votoExistente && !$criado) {
            // Já existe: desmarca
            $votoExistente->delete();
            $avaliacao->decrement('votos_uteis');
            $util = false;
        } else {
            // Não existia: marca
            VotoUtilidade::create(['avaliacao_id' => $avaliacao->id, 'user_id' => $userId]);
            $avaliacao->increment('votos_uteis');
            $util = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'util'        => $util,
                'votos_uteis' => $avaliacao->fresh()->votos_uteis,
            ]);
        }

        return back();
    }
}
