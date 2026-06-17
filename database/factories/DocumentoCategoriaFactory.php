<?php

namespace Database\Factories;

use App\Models\DocumentoCategoria;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoCategoriaFactory extends Factory
{
    protected $model = DocumentoCategoria::class;

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
