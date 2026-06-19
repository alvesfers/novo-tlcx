<?php

namespace Database\Seeders;

use App\Models\FornecedorCamiseta;
use App\Models\FornecedorCamisetaTipo;
use App\Models\FornecedorCamisetaTamanho;
use Illuminate\Database\Seeder;

class FornecedorCamisetaSeeder extends Seeder
{
    /**
     * Seed the application's database with shirt suppliers.
     */
    public function run(): void
    {
        // Fornecedor: Cleide
        $fornecedorCleide = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'Cleide'],
            [
                'descricao' => 'Fornecedora Cleide - Camisetas infantil, babylook e normal',
                'contato' => '',
                'email' => '',
                'ativo' => true,
            ]
        );

        $tiposCleide = [
            ['tipo_camiseta' => 'infantil', 'ordem' => 1],
            ['tipo_camiseta' => 'babylook', 'ordem' => 2],
            ['tipo_camiseta' => 'normal', 'ordem' => 3],
        ];

        foreach ($tiposCleide as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedorCleide->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }

        // Fornecedor: FG Camisetas
        $fornecedorFG = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'FG Camisetas'],
            [
                'descricao' => 'Fornecedora FG Camisetas - babylook e normal',
                'contato' => '',
                'email' => '',
                'ativo' => true,
            ]
        );

        $tiposFG = [
            ['tipo_camiseta' => 'babylook', 'ordem' => 1],
            ['tipo_camiseta' => 'normal', 'ordem' => 2],
        ];

        foreach ($tiposFG as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedorFG->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }

        // Fornecedor: Nissin
        $fornecedorNissin = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'Nissin'],
            [
                'descricao' => 'Fornecedora Nissin - Camisetas infantil, babylook e normal',
                'contato' => '',
                'email' => '',
                'ativo' => true,
            ]
        );

        $tiposNissin = [
            ['tipo_camiseta' => 'infantil', 'ordem' => 1],
            ['tipo_camiseta' => 'babylook', 'ordem' => 2],
            ['tipo_camiseta' => 'normal', 'ordem' => 3],
        ];

        foreach ($tiposNissin as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedorNissin->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }

        // Fornecedor 1: Camisetaria TLC
        $fornecedor1 = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'Camisetaria TLC'],
            [
                'descricao' => 'Fornecedor principal de camisetas para eventos da TLC',
                'contato' => '(11) 98765-4321',
                'email' => 'contato@camisetariatlc.com.br',
                'ativo' => true,
            ]
        );

        // Tipos para Camisetaria TLC
        $tipos1 = [
            ['tipo_camiseta' => 'infantil', 'ordem' => 1],
            ['tipo_camiseta' => 'normal', 'ordem' => 2],
            ['tipo_camiseta' => 'plus', 'ordem' => 3],
            ['tipo_camiseta' => 'babylook', 'ordem' => 4],
            ['tipo_camiseta' => 'oversized', 'ordem' => 5],
        ];

        foreach ($tipos1 as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedor1->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            // Tamanhos para cada tipo
            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }

        // Fornecedor 2: Estampa Brasil
        $fornecedor2 = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'Estampa Brasil'],
            [
                'descricao' => 'Fornecedor alternativo com preços competitivos',
                'contato' => '(11) 99876-5432',
                'email' => 'vendas@estampabrasil.com.br',
                'ativo' => true,
            ]
        );

        // Tipos para Estampa Brasil
        $tipos2 = [
            ['tipo_camiseta' => 'normal', 'ordem' => 1],
            ['tipo_camiseta' => 'plus', 'ordem' => 2],
        ];

        foreach ($tipos2 as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedor2->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }

        // Fornecedor 3: Camisetas Premium
        $fornecedor3 = FornecedorCamiseta::updateOrCreate(
            ['nome' => 'Camisetas Premium'],
            [
                'descricao' => 'Fornecedor premium com qualidade superior',
                'contato' => '(11) 97654-3210',
                'email' => 'premium@camisetaspremium.com.br',
                'ativo' => true,
            ]
        );

        // Tipos para Premium
        $tipos3 = [
            ['tipo_camiseta' => 'infantil', 'ordem' => 1],
            ['tipo_camiseta' => 'normal', 'ordem' => 2],
            ['tipo_camiseta' => 'plus', 'ordem' => 3],
        ];

        foreach ($tipos3 as $tipo) {
            $tipoModel = FornecedorCamisetaTipo::updateOrCreate(
                [
                    'fornecedor_id' => $fornecedor3->id,
                    'tipo_camiseta' => $tipo['tipo_camiseta'],
                ],
                ['ordem' => $tipo['ordem'], 'ativo' => true]
            );

            $this->seedTamanhos($tipoModel, $tipo['tipo_camiseta']);
        }
    }

    private function seedTamanhos($tipoModel, $tipoCamiseta): void
    {
        $tamanhosPorTipo = [
            'infantil' => [
                ['tamanho' => 'P', 'altura' => '50cm', 'largura' => '40cm', 'comprimento' => '45cm'],
                ['tamanho' => 'M', 'altura' => '60cm', 'largura' => '45cm', 'comprimento' => '55cm'],
                ['tamanho' => 'G', 'altura' => '70cm', 'largura' => '50cm', 'comprimento' => '65cm'],
            ],
            'normal' => [
                ['tamanho' => 'P', 'altura' => '68cm', 'largura' => '48cm', 'comprimento' => '70cm'],
                ['tamanho' => 'M', 'altura' => '70cm', 'largura' => '52cm', 'comprimento' => '72cm'],
                ['tamanho' => 'G', 'altura' => '72cm', 'largura' => '55cm', 'comprimento' => '75cm'],
                ['tamanho' => 'GG', 'altura' => '74cm', 'largura' => '58cm', 'comprimento' => '78cm'],
                ['tamanho' => 'GGG', 'altura' => '76cm', 'largura' => '62cm', 'comprimento' => '81cm'],
            ],
            'plus' => [
                ['tamanho' => 'G', 'altura' => '70cm', 'largura' => '60cm', 'comprimento' => '72cm'],
                ['tamanho' => 'GG', 'altura' => '72cm', 'largura' => '65cm', 'comprimento' => '75cm'],
                ['tamanho' => 'GGG', 'altura' => '74cm', 'largura' => '70cm', 'comprimento' => '78cm'],
            ],
            'babylook' => [
                ['tamanho' => 'P', 'altura' => '65cm', 'largura' => '45cm', 'comprimento' => '68cm'],
                ['tamanho' => 'M', 'altura' => '68cm', 'largura' => '48cm', 'comprimento' => '70cm'],
                ['tamanho' => 'G', 'altura' => '70cm', 'largura' => '50cm', 'comprimento' => '72cm'],
            ],
            'oversized' => [
                ['tamanho' => 'GG', 'altura' => '75cm', 'largura' => '65cm', 'comprimento' => '80cm'],
                ['tamanho' => 'GGG', 'altura' => '77cm', 'largura' => '70cm', 'comprimento' => '83cm'],
            ],
        ];

        $tamanhos = $tamanhosPorTipo[$tipoCamiseta] ?? [];

        foreach ($tamanhos as $index => $tamanho) {
            FornecedorCamisetaTamanho::updateOrCreate(
                [
                    'fornecedor_camiseta_tipo_id' => $tipoModel->id,
                    'tamanho' => $tamanho['tamanho'],
                ],
                [
                    'medidas' => [
                        'altura' => $tamanho['altura'],
                        'largura' => $tamanho['largura'],
                        'comprimento' => $tamanho['comprimento'],
                    ],
                    'ordem' => $index + 1,
                    'ativo' => true,
                ]
            );
        }
    }
}
