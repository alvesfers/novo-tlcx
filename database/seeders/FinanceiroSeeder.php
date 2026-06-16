<?php

namespace Database\Seeders;

use App\Models\Entidade;
use App\Models\FinanceiroCategoria;
use Illuminate\Database\Seeder;

class FinanceiroSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasEntrada = [
            'Dízimos',
            'Doações',
            'Inscrições',
            'Ofertas',
            'Contribuições',
        ];

        $categoriasSaida = [
            'Transporte',
            'Alimentação',
            'Inscrição',
            'Doação',
            'Material',
            'Formação',
            'Evento',
            'Outros',
        ];

        $entidades = Entidade::all();

        foreach ($entidades as $entidade) {
            foreach ($categoriasEntrada as $nome) {
                FinanceiroCategoria::firstOrCreate(
                    [
                        'entidade_id' => $entidade->id,
                        'nome' => $nome,
                    ],
                    [
                        'tipo' => 'entrada',
                        'ativo' => true,
                    ]
                );
            }

            foreach ($categoriasSaida as $nome) {
                FinanceiroCategoria::firstOrCreate(
                    [
                        'entidade_id' => $entidade->id,
                        'nome' => $nome,
                    ],
                    [
                        'tipo' => 'saida',
                        'ativo' => true,
                    ]
                );
            }
        }
    }
}
