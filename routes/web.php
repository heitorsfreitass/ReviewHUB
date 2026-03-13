<?php

use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/**
 * CONCEITO: Rotas do Laravel
 * --------------------------
 * Route::get/post/put/patch/delete → verbos HTTP
 * Route::resource() → gera automaticamente as 7 rotas REST
 * middleware('auth') → exige autenticação
 * ->name() → nomeia a rota para uso no Blade/Controller
 *
 * Nomes das rotas do resource 'produtos':
 *   produtos.index   → GET  /produtos
 *   produtos.create  → GET  /produtos/create
 *   produtos.store   → POST /produtos
 *   produtos.show    → GET  /produtos/{produto}
 *   produtos.edit    → GET  /produtos/{produto}/edit
 *   produtos.update  → PUT  /produtos/{produto}
 *   produtos.destroy → DELETE /produtos/{produto}
 */

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Produtos — leitura é pública, escrita exige login
Route::resource('produtos', ProdutoController::class)
    ->only(['index', 'show']);

Route::resource('produtos', ProdutoController::class)
    ->except(['index', 'show'])
    ->middleware('auth');

// Avaliações — rotas aninhadas: /produtos/{produto}/avaliacoes/...
Route::prefix('produtos/{produto}/avaliacoes')
    ->name('produtos.avaliacoes.')
    ->middleware('auth')
    ->group(function () {
        Route::get('create',           [AvaliacaoController::class, 'create'])->name('create');
        Route::post('/',               [AvaliacaoController::class, 'store'])->name('store');
        Route::get('{avaliacao}/edit', [AvaliacaoController::class, 'edit'])->name('edit');
        Route::put('{avaliacao}',      [AvaliacaoController::class, 'update'])->name('update');
        Route::delete('{avaliacao}',   [AvaliacaoController::class, 'destroy'])->name('destroy');
    });

// Toggle "útil" — pode ser AJAX
Route::post('avaliacoes/{avaliacao}/util', [AvaliacaoController::class, 'toggleUtil'])
    ->name('avaliacoes.util')
    ->middleware('auth');

// Perfil do usuário (Breeze gera estes)
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas de autenticação geradas pelo Breeze
require __DIR__.'/auth.php';
