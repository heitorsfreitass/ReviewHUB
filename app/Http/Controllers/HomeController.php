<?php

namespace App\Http\Controllers;

use App\Models\Avaliacao;
use App\Models\Categoria;
use App\Models\Produto;

class HomeController extends Controller
{
    public function index()
    {
        // Produtos em destaque para a home
        $maisAvaliados  = Produto::with('categoria')->maisAvaliados()->take(6)->get();
        $melhorNotados  = Produto::with('categoria')->melhorNotados()->take(6)->get();
        $recemAdicionados = Produto::with('categoria')->recentes()->take(4)->get();
        $categorias     = Categoria::withCount('produtos')->orderByDesc('produtos_count')->take(8)->get();

        $totalProdutos  = Produto::count();
        $totalAvaliacoes = Avaliacao::count();

        return view('home', compact(
            'maisAvaliados', 'melhorNotados', 'recemAdicionados',
            'categorias', 'totalProdutos', 'totalAvaliacoes'
        ));
    }
}
