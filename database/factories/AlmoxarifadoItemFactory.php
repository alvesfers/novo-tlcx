<?php

namespace Database\Factories;

use App\Models\AlmoxarifadoItem;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlmoxarifadoItemFactory extends Factory
{
    protected $model = AlmoxarifadoItem::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory(),
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
            'unidade_medida' => 'unidade',
            'quantidade_atual' => $this->faker->numberBetween(0, 100),
            'quantidade_minima' => $this->faker->numberBetween(10, 50),
            'status' => 'ativo',
        ];
    }
}
