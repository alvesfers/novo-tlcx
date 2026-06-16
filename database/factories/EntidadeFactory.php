<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntidadeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome' => fake()->company(),
            'tipo_entidade' => fake()->randomElement(['diocese', 'nucleo', 'secretaria']),
            'entidade_pai_id' => null,
            'ativo' => true,
        ];
    }
}
