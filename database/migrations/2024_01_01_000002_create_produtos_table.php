<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // categorias de produto (Eletrônicos, Roupas, Alimentos, etc.)
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 80)->unique();
            $table->string('icone', 10)->default('📦'); // emoji para exibir na UI
            $table->string('slug', 80)->unique();
            $table->timestamps();
        });

        /**
         * - foreignId('user_id') é atalho para $table->unsignedBigInteger('user_id')
         * - ->constrained() adiciona a FK apontando para a tabela "users"
         * - ->cascadeOnDelete() apaga os produtos se o usuário for apagado
         */
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');                             // nome original do produto
            $table->string('slug')->unique();                   // url amigável: "iphone-15-pro"
            $table->string('marca')->nullable();
            $table->text('descricao')->nullable();
            $table->string('imagem');                           // path do upload obrigatório
            $table->foreignId('categoria_id')
                  ->constrained()
                  ->restrictOnDelete();                         // impede apagar categoria com produtos
            $table->foreignId('user_id')                       // quem cadastrou
                  ->constrained()
                  ->cascadeOnDelete();

            // campos calculados para performance
            $table->unsignedInteger('total_avaliacoes')->default(0);
            $table->decimal('media_nota', 3, 2)->default(0);

            $table->timestamps();

            $table->index('nome');
            $table->index('media_nota');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
        Schema::dropIfExists('categorias');
    }
};
