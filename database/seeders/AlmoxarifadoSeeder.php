<?php

namespace Database\Seeders;

use App\Models\AlmoxarifadoCategoria;
use App\Models\AlmoxarifadoItem;
use App\Models\Entidade;
use Illuminate\Database\Seeder;

class AlmoxarifadoSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasPadrao = [
            'Cozinha',
            'Liturgia',
            'Limpeza',
            'Papelaria',
            'Som e Mídia',
            'Decoração',
            'Diversos',
        ];

        $itensPadrao = [
            ['nome' => 'Copos descartáveis', 'unidade' => 'pacote', 'quantidade_minima' => 100],
            ['nome' => 'Guardanapos', 'unidade' => 'pacote', 'quantidade_minima' => 50],
            ['nome' => 'Extensão elétrica', 'unidade' => 'unidade', 'quantidade_minima' => 3],
            ['nome' => 'Caixa de som', 'unidade' => 'unidade', 'quantidade_minima' => 1],
            ['nome' => 'Toalhas', 'unidade' => 'unidade', 'quantidade_minima' => 5],
            ['nome' => 'Velas', 'unidade' => 'unidade', 'quantidade_minima' => 10],
            ['nome' => 'Canetas', 'unidade' => 'caixa', 'quantidade_minima' => 2],
        ];

        $entidades = Entidade::where('ativo', true)->get();

        foreach ($entidades as $entidade) {
            foreach ($categoriasPadrao as $i => $nomeCategoria) {
                $categoria = AlmoxarifadoCategoria::create([
                    'entidade_id' => $entidade->id,
                    'nome' => $nomeCategoria,
                    'descricao' => "Categoria de $nomeCategoria da entidade {$entidade->nome}",
                    'ativo' => true,
                ]);

                $itemsComCategoria = match($i) {
                    0 => array_slice($itensPadrao, 0, 3),
                    1 => array_slice($itensPadrao, 3, 2),
                    2 => array_slice($itensPadrao, 5, 1),
                    default => [$itensPadrao[6]],
                };

                foreach ($itemsComCategoria as $item) {
                    AlmoxarifadoItem::create([
                        'entidade_id' => $entidade->id,
                        'almoxarifado_categoria_id' => $categoria->id,
                        'nome' => $item['nome'],
                        'descricao' => "Item: {$item['nome']}",
                        'unidade_medida' => $item['unidade'],
                        'quantidade_atual' => rand(10, 100),
                        'quantidade_minima' => $item['quantidade_minima'],
                        'status' => 'ativo',
                    ]);
                }
            }
        }
    }
}
