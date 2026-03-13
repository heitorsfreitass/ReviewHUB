<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();

            // Relacionamentos
            $table->foreignId('produto_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Campos da avaliação
            $table->unsignedTinyInteger('nota');                // 1 a 5 estrelas
            $table->string('titulo', 120);
            $table->text('conteudo');
            $table->decimal('preco_pago', 10, 2)->nullable();   // preço que pagou
            $table->string('loja', 120)->nullable();            // nome da loja/site
            $table->string('url_loja', 500)->nullable();        // link para a loja
            $table->boolean('recomenda')->default(true);        // "você recomenda?"
            $table->unsignedInteger('votos_uteis')->default(0); // quantas pessoas acharam útil

            $table->timestamps();

            /**
             * CONCEITO: Unique composto
             * Um usuário só pode ter UMA avaliação por produto.
             */
            $table->unique(['produto_id', 'user_id']);
        });

        /**
         * Tabela pivot: usuários que marcaram uma avaliação como "útil"
         * Evita que o mesmo usuário vote várias vezes.
         */
        Schema::create('votos_utilidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avaliacao_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('avaliacao_id')
                ->references('id')
                ->on('avaliacoes')       // nome explícito da tabela
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->unique(['avaliacao_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votos_utilidade');
        Schema::dropIfExists('avaliacoes');
    }
};
