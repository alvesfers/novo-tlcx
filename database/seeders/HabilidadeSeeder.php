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
                ['nome' => 'Percussão', 'descricao' => 'Habilidade em instrumentos de percussão'],
                ['nome' => 'Flauta', 'descricao' => 'Habilidade em tocar flauta'],
            ],
            'Apoio' => [
                ['nome' => 'Limpeza', 'descricao' => 'Organização e limpeza de ambientes'],
                ['nome' => 'Decoração', 'descricao' => 'Decoração e organização visual de espaços'],
                ['nome' => 'Culinária', 'descricao' => 'Preparo de alimentos e refeições'],
                ['nome' => 'Desenho', 'descricao' => 'Criação de desenhos e artes visuais'],
                ['nome' => 'Experiência', 'descricao' => 'Experiência geral em apoio logístico'],
            ],
            'Espiritualidade e Formacao' => [
                ['nome' => 'Catequese', 'descricao' => 'Ensino de doutrina religiosa'],
                ['nome' => 'Retiros', 'descricao' => 'Organização e condução de retiros espirituais'],
                ['nome' => 'Liturgia', 'descricao' => 'Conhecimento e prática da liturgia'],
                ['nome' => 'Formação Bíblica', 'descricao' => 'Conhecimento profundo da Bíblia'],
            ],
            'Eventos' => [
                ['nome' => 'Organização', 'descricao' => 'Planejamento e organização de eventos'],
                ['nome' => 'Fotografia', 'descricao' => 'Fotografia profissional de eventos'],
                ['nome' => 'Comunicação', 'descricao' => 'Comunicação e divulgação de eventos'],
                ['nome' => 'Logística', 'descricao' => 'Gestão logística de eventos'],
            ],
            'Intercessao' => [
                ['nome' => 'Oração', 'descricao' => 'Liderança em momentos de oração'],
                ['nome' => 'Louvor', 'descricao' => 'Condução de louvor e adoração'],
                ['nome' => 'Meditação', 'descricao' => 'Facilitação de meditação espiritual'],
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
