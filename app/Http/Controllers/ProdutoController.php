<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProdutoRequest;
use App\Models\Categoria;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * index()   → GET  /produtos              → listar todos
 * create()  → GET  /produtos/create       → formulário de criação
 * store()   → POST /produtos              → salvar novo
 * show()    → GET  /produtos/{produto}    → ver um produto
 * edit()    → GET  /produtos/{produto}/edit → formulário de edição
 * update()  → PUT  /produtos/{produto}    → salvar edição
 * destroy() → DELETE /produtos/{produto}  → apagar
 *
 * Route::resource('produtos', ProdutoController::class)
 */
class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with(['categoria', 'user'])
                        ->withCount('avaliacoes');

        // Busca por texto
        if ($termo = $request->input('busca')) {
            $query->buscar($termo);
        }

        // Filtro por categoria
        if ($categoriaSlug = $request->input('categoria')) {
            $categoria = Categoria::where('slug', $categoriaSlug)->firstOrFail();
            $query->daCategoria($categoria->id);
        }

        // Ordenação
        match ($request->input('ordem', 'recentes')) {
            'mais_avaliados' => $query->maisAvaliados(),
            'melhor_nota'    => $query->melhorNotados(),
            default          => $query->recentes(),
        };

        $produtos   = $query->paginate(12)->withQueryString();
        $categorias = Categoria::withCount('produtos')->orderBy('nome')->get();

        return view('produtos.index', compact('produtos', 'categorias'));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('produtos.create', compact('categorias'));
    }

    public function store(StoreProdutoRequest $request)
    {
        /**
         * $request->file('imagem') retorna um UploadedFile
         * ->store('produtos', 'public') salva em storage/app/public/produtos/
         * e retorna o path relativo
         * pra funfar em url pub: php artisan storage:link
         * Cria um link simbólico public/storage → storage/app/public
         */
        $imagemPath = $request->file('imagem')->store('produtos', 'public');

        $produto = Produto::create([
            ...$request->validated(),
            'imagem'  => $imagemPath,
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('produtos.show', $produto)
            ->with('sucesso', "Produto \"{$produto->nome}\" cadastrado! Agora adicione a primeira avaliação.");
    }

    public function show(Produto $produto)
    {
        $produto->load([
            'categoria',
            'user',
            'avaliacoes' => fn($q) => $q->with('user')->latest(),
        ]);

        // Distribuição de notas para o gráfico de barras
        $distribuicaoNotas = $produto->avaliacoes
            ->groupBy('nota')
            ->map->count()
            ->sortKeysDesc();

        // Verifica se o usuário logado já avaliou
        $jaAvaliou = auth()->check()
            ? $produto->avaliacoes->contains('user_id', auth()->id())
            : false;

        // IDs de avaliações que o usuário achou úteis
        $votosDoUsuario = auth()->check()
            ? auth()->user()->votosUteis()->pluck('avaliacao_id')->toArray()
            : [];

        return view('produtos.show', compact(
            'produto', 'distribuicaoNotas', 'jaAvaliou', 'votosDoUsuario'
        ));
    }

    public function edit(Produto $produto)
    {
        $this->authorize('update', $produto);
        $categorias = Categoria::orderBy('nome')->get();
        return view('produtos.edit', compact('produto', 'categorias'));
    }

    public function update(StoreProdutoRequest $request, Produto $produto)
    {
        $this->authorize('update', $produto);

        $dados = $request->validated();

        if ($request->hasFile('imagem')) {
            Storage::disk('public')->delete($produto->imagem); // apaga a antiga
            $dados['imagem'] = $request->file('imagem')->store('produtos', 'public');
        } else {
            unset($dados['imagem']); // mantém a imagem atual
        }

        $produto->update($dados);

        return redirect()
            ->route('produtos.show', $produto)
            ->with('sucesso', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $this->authorize('delete', $produto);

        Storage::disk('public')->delete($produto->imagem);
        $produto->delete();

        return redirect()
            ->route('produtos.index')
            ->with('sucesso', 'Produto removido.');
    }
}
