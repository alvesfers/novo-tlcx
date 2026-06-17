<?php

namespace Database\Factories;

use App\Models\Tarefa;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaFactory extends Factory
{
    protected $model = Tarefa::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory(),
            'titulo' => $this->faker->sentence(),
            'descricao' => $this->faker->paragraph(),
            'status' => 'pendente',
            'prioridade' => 'media',
            'data_inicio' => $this->faker->dateTime(),
            'data_limite' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
        ];
    }
}
