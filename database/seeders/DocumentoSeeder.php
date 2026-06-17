<?php

namespace Database\Seeders;

use App\Models\DocumentoCategoria;
use App\Models\Entidade;
use Illuminate\Database\Seeder;

class DocumentoSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasPadrao = [
            'Geral',
            'Atas',
            'Financeiro',
            'Eventos',
            'Formação',
            'Liturgia',
            'Imagens',
        ];

        $entidades = Entidade::where('ativo', true)->get();

        foreach ($entidades as $entidade) {
            foreach ($categoriasPadrao as $nomeCategoria) {
                DocumentoCategoria::create([
                    'entidade_id' => $entidade->id,
                    'nome' => $nomeCategoria,
                    'descricao' => "Categoria de $nomeCategoria da entidade {$entidade->nome}",
                    'ativo' => true,
                ]);
            }
        }
    }
}
