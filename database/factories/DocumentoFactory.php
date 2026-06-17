<?php

namespace Database\Factories;

use App\Models\Documento;
use App\Models\Entidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition(): array
    {
        return [
            'entidade_id' => Entidade::factory(),
            'titulo' => $this->faker->sentence(),
            'descricao' => $this->faker->paragraph(),
            'arquivo_nome_original' => $this->faker->fileName(),
            'arquivo_nome_armazenado' => $this->faker->uuid() . '.pdf',
            'arquivo_path' => 'documentos/' . $this->faker->uuid() . '.pdf',
            'arquivo_mime' => 'application/pdf',
            'arquivo_tamanho' => 1024 * 100,
            'tipo_documento' => 'geral',
            'visibilidade' => 'privado',
            'ativo' => true,
        ];
    }
}
