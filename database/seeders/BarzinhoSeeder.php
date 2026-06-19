<?php

namespace Database\Seeders;

use App\Models\Barzinho;
use App\Models\BarzinhoProduto;
use App\Models\BarzinhoCombo;
use App\Models\BarzinhoCombItem;
use App\Models\Evento;
use Illuminate\Database\Seeder;

class BarzinhoSeeder extends Seeder
{
    /**
     * Seed the application's database with snack bars and products.
     */
    public function run(): void
    {
        // Busca o evento 19° TLC Santa Paulina especificamente
        $evento19TLCSnthPaulina = Evento::where('nome', '19º TLC Santa Paulina')
            ->orWhere('nome', '19 TLC Santa Paulina')
            ->first();

        if ($evento19TLCSnthPaulina) {
            $this->criarBarzinho($evento19TLCSnthPaulina);
        }

        // Também processa os primeiros 3 eventos como antes (para compatibilidade)
        $eventos = Evento::limit(3)->get();

        foreach ($eventos as $evento) {
            if ($evento19TLCSnthPaulina && $evento->id === $evento19TLCSnthPaulina->id) {
                continue; // Já processamos
            }
            $this->criarBarzinhoDefault($evento);
        }
    }

    private function criarBarzinho($evento): void
    {
        $barzinho = Barzinho::updateOrCreate(
            [
                'evento_id' => $evento->id,
                'nome' => 'Loja do ' . $evento->nome,
            ],
            [
                'descricao' => 'Loja de vendas durante o evento',
                'ativo' => true,
            ]
        );

        // Produtos específicos para 19° TLC Santa Paulina
        $produtos = [
            [
                'nome' => 'Coca Cola Pequena Zero',
                'descricao' => 'Coca Cola 250ml Zero Açúcar',
                'preco_custo' => 1.50,
                'preco_venda' => 3.50,
                'quantidade' => 200,
            ],
            [
                'nome' => 'Coca Cola Pequena Normal',
                'descricao' => 'Coca Cola 250ml Normal',
                'preco_custo' => 1.50,
                'preco_venda' => 3.50,
                'quantidade' => 150,
            ],
            [
                'nome' => 'Guaraná Pequeno',
                'descricao' => 'Guaraná 250ml',
                'preco_custo' => 1.20,
                'preco_venda' => 3.00,
                'quantidade' => 150,
            ],
            [
                'nome' => 'Twix',
                'descricao' => 'Chocolate Twix',
                'preco_custo' => 2.00,
                'preco_venda' => 4.50,
                'quantidade' => 100,
            ],
            [
                'nome' => 'Fini',
                'descricao' => 'Doce Fini',
                'preco_custo' => 1.00,
                'preco_venda' => 2.50,
                'quantidade' => 150,
            ],
            [
                'nome' => 'Paçoca',
                'descricao' => 'Paçoca de amendoim',
                'preco_custo' => 1.50,
                'preco_venda' => 3.50,
                'quantidade' => 120,
            ],
            [
                'nome' => 'Terços',
                'descricao' => 'Terços religiosos',
                'preco_custo' => 5.00,
                'preco_venda' => 12.00,
                'quantidade' => 50,
            ],
            [
                'nome' => 'Ícones de Guadalupe',
                'descricao' => 'Ícones religiosos de Nossa Senhora de Guadalupe',
                'preco_custo' => 8.00,
                'preco_venda' => 18.00,
                'quantidade' => 30,
            ],
            [
                'nome' => 'Pirulito',
                'descricao' => 'Pirulito sortido',
                'preco_custo' => 0.50,
                'preco_venda' => 1.50,
                'quantidade' => 200,
            ],
        ];

        $produtosModels = [];
        foreach ($produtos as $produto) {
            $p = BarzinhoProduto::updateOrCreate(
                [
                    'barzinho_id' => $barzinho->id,
                    'nome' => $produto['nome'],
                ],
                [
                    'descricao' => $produto['descricao'],
                    'preco_custo' => $produto['preco_custo'],
                    'preco_venda' => $produto['preco_venda'],
                    'quantidade' => $produto['quantidade'],
                    'ativo' => true,
                ]
            );
            $produtosModels[] = $p;
        }
    }

    private function criarBarzinhoDefault($evento): void
    {
        $barzinho = Barzinho::updateOrCreate(
            [
                'evento_id' => $evento->id,
                'nome' => 'Loja do ' . $evento->nome,
            ],
            [
                'descricao' => 'Loja de vendas e bebidas durante o evento',
                'ativo' => true,
            ]
        );

        // Produtos padrão
        $produtos = [
            [
                'nome' => 'Refrigerante Lata',
                'descricao' => 'Refrigerante 350ml',
                'preco_custo' => 2.50,
                'preco_venda' => 5.00,
                'quantidade' => 200,
            ],
            [
                'nome' => 'Suco Natural',
                'descricao' => 'Suco natural de frutas 500ml',
                'preco_custo' => 3.00,
                'preco_venda' => 6.00,
                'quantidade' => 150,
            ],
            [
                'nome' => 'Água Mineral',
                'descricao' => 'Água mineral 1.5L',
                'preco_custo' => 1.50,
                'preco_venda' => 3.00,
                'quantidade' => 300,
            ],
            [
                'nome' => 'Lanche - Pão com Queijo',
                'descricao' => 'Pão integral com queijo derretido',
                'preco_custo' => 5.00,
                'preco_venda' => 10.00,
                'quantidade' => 100,
            ],
            [
                'nome' => 'Lanche - Salgado',
                'descricao' => 'Salgado assado (pastel, coxinha ou esfiha)',
                'preco_custo' => 4.00,
                'preco_venda' => 8.00,
                'quantidade' => 150,
            ],
            [
                'nome' => 'Bolo/Biscoito',
                'descricao' => 'Bolo ou biscoito doce 100g',
                'preco_custo' => 3.50,
                'preco_venda' => 7.00,
                'quantidade' => 80,
            ],
            [
                'nome' => 'Café/Chá',
                'descricao' => 'Café ou chá quente - xícara 200ml',
                'preco_custo' => 1.00,
                'preco_venda' => 3.00,
                'quantidade' => 200,
            ],
            [
                'nome' => 'Sanduiche',
                'descricao' => 'Sanduiche com frios e legumes',
                'preco_custo' => 8.00,
                'preco_venda' => 15.00,
                'quantidade' => 60,
            ],
        ];

        $produtosModels = [];
        foreach ($produtos as $produto) {
            $p = BarzinhoProduto::updateOrCreate(
                [
                    'barzinho_id' => $barzinho->id,
                    'nome' => $produto['nome'],
                ],
                [
                    'descricao' => $produto['descricao'],
                    'preco_custo' => $produto['preco_custo'],
                    'preco_venda' => $produto['preco_venda'],
                    'quantidade' => $produto['quantidade'],
                    'ativo' => true,
                ]
            );
            $produtosModels[] = $p;
        }

        // Cria combos usando os produtos criados
        if (count($produtosModels) >= 3) {
            $combo1 = BarzinhoCombo::updateOrCreate(
                [
                    'barzinho_id' => $barzinho->id,
                    'nome' => 'Combo Bebida + Lanche',
                ],
                [
                    'descricao' => '1 Refrigerante + 1 Lanche (economia de R$ 1,00)',
                    'preco_venda' => 12.00,
                    'ativo' => true,
                ]
            );

            // Adiciona itens ao combo 1
            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo1->id,
                    'produto_id' => $produtosModels[0]->id,
                ],
                ['quantidade' => 1]
            );

            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo1->id,
                    'produto_id' => $produtosModels[3]->id,
                ],
                ['quantidade' => 1]
            );

            $combo2 = BarzinhoCombo::updateOrCreate(
                [
                    'barzinho_id' => $barzinho->id,
                    'nome' => 'Combo Café + Bolo',
                ],
                [
                    'descricao' => '1 Café/Chá + 1 Bolo/Biscoito (economia de R$ 1,00)',
                    'preco_venda' => 9.00,
                    'ativo' => true,
                ]
            );

            // Adiciona itens ao combo 2
            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo2->id,
                    'produto_id' => $produtosModels[6]->id,
                ],
                ['quantidade' => 1]
            );

            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo2->id,
                    'produto_id' => $produtosModels[5]->id,
                ],
                ['quantidade' => 1]
            );

            // Combo completo
            $combo3 = BarzinhoCombo::updateOrCreate(
                [
                    'barzinho_id' => $barzinho->id,
                    'nome' => 'Combo Premium',
                ],
                [
                    'descricao' => 'Sanduiche + Bebida + Bolo (economia de R$ 2,00)',
                    'preco_venda' => 22.00,
                    'ativo' => true,
                ]
            );

            // Adiciona itens ao combo 3
            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo3->id,
                    'produto_id' => $produtosModels[7]->id,
                ],
                ['quantidade' => 1]
            );

            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo3->id,
                    'produto_id' => $produtosModels[0]->id,
                ],
                ['quantidade' => 1]
            );

            BarzinhoCombItem::updateOrCreate(
                [
                    'combo_id' => $combo3->id,
                    'produto_id' => $produtosModels[5]->id,
                ],
                ['quantidade' => 1]
            );
        }
    }
}
