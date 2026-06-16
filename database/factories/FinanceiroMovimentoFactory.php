<?php

namespace Database\Factories;

use App\Models\Entidade;
use App\Models\FinanceiroCategoria;
use App\Models\FinanceiroMovimento;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceiroMovimentoFactory extends Factory
{
    protected $model = FinanceiroMovimento::class;

    public function definition(): array
    {
        $entidade = Entidade::factory()->create();
        $categoria = FinanceiroCategoria::factory()->create([
            'entidade_id' => $entidade->id,
        ]);

        return [
            'entidade_id' => $entidade->id,
            'financeiro_categoria_id' => $categoria->id,
            'evento_id' => null,
            'tipo' => $categoria->tipo,
            'descricao' => fake()->sentence(),
            'valor' => fake()->randomFloat(2, 10, 1000),
            'data_movimento' => fake()->dateTimeBetween('-30 days')->format('Y-m-d'),
            'forma_pagamento' => fake()->randomElement(['dinheiro', 'pix', 'transferencia', 'cartao', 'cheque', 'outro']),
            'comprovante_url' => null,
            'observacao' => fake()->optional()->sentence(),
        ];
    }
}
