<?php

namespace Database\Seeders;

use App\Models\Entidade;
use App\Models\Tarefa;
use App\Models\TarefaCategoria;
use Illuminate\Database\Seeder;

class TarefaSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasPadrao = [
            'Geral',
            'Evento',
            'Financeiro',
            'Almoxarifado',
            'Comunicação',
            'Liturgia',
            'Cozinha',
        ];

        $tarefasExemplo = [
            ['titulo' => 'Comprar materiais do evento', 'prioridade' => 'alta', 'status' => 'pendente'],
            ['titulo' => 'Conferir estoque da cozinha', 'prioridade' => 'media', 'status' => 'em_andamento'],
            ['titulo' => 'Confirmar presença dos dirigentes', 'prioridade' => 'alta', 'status' => 'pendente'],
            ['titulo' => 'Preparar lista de chamada', 'prioridade' => 'media', 'status' => 'pendente'],
            ['titulo' => 'Separar itens do almoxarifado', 'prioridade' => 'media', 'status' => 'em_andamento'],
        ];

        $entidades = Entidade::where('ativo', true)->get();

        foreach ($entidades as $entidade) {
            // Criar categorias padrão
            foreach ($categoriasPadrao as $nomeCategoria) {
                TarefaCategoria::create([
                    'entidade_id' => $entidade->id,
                    'nome' => $nomeCategoria,
                    'descricao' => "Categoria de $nomeCategoria",
                    'cor' => '#' . substr(md5($nomeCategoria), 0, 6),
                    'ativo' => true,
                ]);
            }

            // Criar tarefas de exemplo
            $categorias = TarefaCategoria::where('entidade_id', $entidade->id)->get();

            foreach ($tarefasExemplo as $tarefa) {
                Tarefa::create([
                    'entidade_id' => $entidade->id,
                    'tarefa_categoria_id' => $categorias->random()->id,
                    'titulo' => $tarefa['titulo'],
                    'descricao' => "Descrição da tarefa: {$tarefa['titulo']}",
                    'status' => $tarefa['status'],
                    'prioridade' => $tarefa['prioridade'],
                    'data_inicio' => now()->subDays(rand(0, 5)),
                    'data_limite' => now()->addDays(rand(1, 14)),
                    'criada_por_user_id' => 1,
                ]);
            }
        }
    }
}
