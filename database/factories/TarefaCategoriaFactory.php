<?php

namespace Database\Factories;

use App\Models\TarefaCategoria;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarefaCategoriaFactory extends Factory
{
    protected $model = TarefaCategoria::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory(),
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
            'cor' => $this->faker->hexColor(),
            'ativo' => true,
        ];
    }
}
