<?php

namespace Database\Seeders;

use App\Models\Avaliacao;
use App\Models\Categoria;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * CONCEITO: Seeders
 * -----------------
 * Populam o banco com dados de teste/exemplo.
 * Rode com: php artisan db:seed
 * Ou junto com migrate: php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuários de teste
        $admin = User::create([
            'name'     => 'Admin ReviewHub',
            'email'    => 'admin@reviewhub.com',
            'password' => Hash::make('password'),
        ]);

        $usuarios = collect([
            ['name' => 'João Silva',   'email' => 'joao@email.com'],
            ['name' => 'Maria Santos', 'email' => 'maria@email.com'],
            ['name' => 'Pedro Costa',  'email' => 'pedro@email.com'],
            ['name' => 'Ana Lima',     'email' => 'ana@email.com'],
        ])->map(fn($u) => User::create([...$u, 'password' => Hash::make('password')]));

        // Categorias
        $cats = [
            ['nome' => 'Eletrônicos',      'icone' => '💻', 'slug' => 'eletronicos'],
            ['nome' => 'Celulares',        'icone' => '📱', 'slug' => 'celulares'],
            ['nome' => 'Roupas e Moda',    'icone' => '👕', 'slug' => 'roupas'],
            ['nome' => 'Casa e Decoração', 'icone' => '🏠', 'slug' => 'casa'],
            ['nome' => 'Esportes',         'icone' => '⚽', 'slug' => 'esportes'],
            ['nome' => 'Alimentos',        'icone' => '🍕', 'slug' => 'alimentos'],
            ['nome' => 'Livros',           'icone' => '📚', 'slug' => 'livros'],
            ['nome' => 'Jogos',            'icone' => '🎮', 'slug' => 'jogos'],
            ['nome' => 'Beleza e Saúde',   'icone' => '💄', 'slug' => 'beleza'],
            ['nome' => 'Ferramentas',      'icone' => '🔧', 'slug' => 'ferramentas'],
        ];

        foreach ($cats as $cat) {
            Categoria::create($cat);
        }

        // Produtos de exemplo
        $produtos = [
            ['nome' => 'iPhone 15 Pro Max',     'marca' => 'Apple',   'categoria' => 'celulares'],
            ['nome' => 'Galaxy S24 Ultra',       'marca' => 'Samsung', 'categoria' => 'celulares'],
            ['nome' => 'AirPods Pro (2ª geração)','marca' => 'Apple',  'categoria' => 'eletronicos'],
            ['nome' => 'PlayStation 5',          'marca' => 'Sony',    'categoria' => 'jogos'],
            ['nome' => 'Kindle Paperwhite',      'marca' => 'Amazon',  'categoria' => 'eletronicos'],
            ['nome' => 'Tênis Air Max 270',      'marca' => 'Nike',    'categoria' => 'esportes'],
            ['nome' => 'Cafeteira Nespresso Vertuo', 'marca' => 'Nespresso', 'categoria' => 'casa'],
        ];

        foreach ($produtos as $index => $p) {
            $categoria = Categoria::where('slug', $p['categoria'])->first();
            $criador   = $usuarios->random();

            $produto = Produto::create([
                'nome'         => $p['nome'],
                'marca'        => $p['marca'],
                'categoria_id' => $categoria->id,
                'user_id'      => $criador->id,
                'descricao'    => "Produto de exemplo: {$p['nome']}. Cadastrado para demonstração do ReviewHub.",
                'imagem'       => 'produtos/placeholder.jpg',
            ]);

            // Cria 2-4 avaliações por produto
            $avaliadores = $usuarios->shuffle()->take(rand(2, 4));
            foreach ($avaliadores as $avaliador) {
                if ($avaliador->id === $criador->id) continue;

                Avaliacao::create([
                    'produto_id' => $produto->id,
                    'user_id'    => $avaliador->id,
                    'nota'       => rand(3, 5),
                    'titulo'     => "Minha experiência com {$p['nome']}",
                    'conteudo'   => "Comprei há alguns meses e estou muito satisfeito. O produto chegou bem embalado, dentro do prazo. A qualidade é excelente para o preço pago. Recomendo a todos que estão pensando em comprar.",
                    'preco_pago' => rand(50, 5000) + rand(0, 99) / 100,
                    'loja'       => ['Amazon', 'Mercado Livre', 'Magazine Luiza', 'Americanas'][rand(0, 3)],
                    'recomenda'  => rand(0, 4) > 0, // 80% recomenda
                ]);
            }
        }

        $this->command->info('✅ Banco populado! Login: admin@reviewhub.com / password');
    }
}
