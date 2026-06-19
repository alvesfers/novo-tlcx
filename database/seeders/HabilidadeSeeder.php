<?php

namespace Database\Seeders;

use App\Models\Entidade;
use App\Models\Habilidade;
use Illuminate\Database\Seeder;

class HabilidadeSeeder extends Seeder
{
    public function run(): void
    {
        $secretarias = Entidade::where('tipo_entidade', 'secretaria')->get();

        $habilidadesPorSecretaria = [
            'Musica' => [
                ['nome' => 'Violão', 'descricao' => 'Habilidade em tocar violão'],
                ['nome' => 'Canto', 'descricao' => 'Habilidade vocal para cânticos'],
                ['nome' => 'Teclado', 'descricao' => 'Habilidade em tocar teclado'],
                ['nome' => 'Cajon', 'descricao' => 'Habilidade em instrumentos de cajon'],
            ],
            'Apoio' => [
                ['nome' => 'Desenho', 'descricao' => 'Criação de desenhos e artes visuais'],
                ['nome' => 'Experiência', 'descricao' => 'Experiência geral'],
            ],
            'Eventos' => [
                ['nome' => 'Fotografia', 'descricao' => 'Fotografia profissional de eventos'],
            ],
            'Intercessao' => [
                ['nome' => 'Experiência', 'descricao' => 'Experiência geral'],
            ],
        ];

        foreach ($secretarias as $secretaria) {
            $nomeSecretaria = str_replace('-', '', $secretaria->nome);
            $nomeSecretaria = preg_replace('/\s+/', '', $nomeSecretaria);

            foreach (array_keys($habilidadesPorSecretaria) as $key) {
                $keyFormatted = preg_replace('/\s+/', '', $key);
                if (stripos($nomeSecretaria, $keyFormatted) !== false || stripos($keyFormatted, $nomeSecretaria) !== false) {
                    foreach ($habilidadesPorSecretaria[$key] as $habilidade) {
                        Habilidade::firstOrCreate(
                            [
                                'entidade_id' => $secretaria->id,
                                'nome' => $habilidade['nome'],
                            ],
                            [
                                'descricao' => $habilidade['descricao'],
                                'ativo' => true,
                            ]
                        );
                    }
                    break;
                }
            }
        }
    }
}
