<?php

namespace Database\Factories;

use App\Models\Entidade;
use App\Models\FinanceiroCategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceiroCategoriaFactory extends Factory
{
    protected $model = FinanceiroCategoria::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory()->create()->id,
            'nome' => fake()->word(),
            'tipo' => fake()->randomElement(['entrada', 'saida']),
            'ativo' => true,
        ];
    }
}
