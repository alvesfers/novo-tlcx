<?php

namespace Database\Factories;

use App\Models\AlmoxarifadoCategoria;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlmoxarifadoCategoriaFactory extends Factory
{
    protected $model = AlmoxarifadoCategoria::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory(),
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
            'ativo' => true,
        ];
    }
}
