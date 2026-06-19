<?php

namespace Database\Seeders;

use App\Models\Dirigente;
use App\Models\Evento;
use App\Models\FuncaoDirigente;
use App\Models\EventoParticipante;
use Illuminate\Database\Seeder;

class DirigenteEventoFuncaoSeeder extends Seeder
{
    public function run(): void
    {
        $fernando = Dirigente::where('nome', 'Fernando')->first();
        if (!$fernando) {
            return;
        }

        // Mapping de eventos para funções
        $eventosFuncoes = [
            // Santa Paulina
            ['nome' => '18 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Música'],
            ['nome' => '17 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Chefe'],
            ['nome' => '16 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Subchefe Sala'],
            ['nome' => '15 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Subchefe Escondido'],
            ['nome' => '14 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Música'],
            ['nome' => '13 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Música'],
            ['nome' => '12 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Música'],
            ['nome' => '11 TLC Santa Paulina', 'nucleo' => 'Núcleo Santa Paulina', 'funcao' => 'Ordem'],

            // Cidinha
            ['nome' => '9.6 TLC Cidinha', 'nucleo' => 'Núcleo Cidinha', 'funcao' => 'EQR'],

            // Ap Grajau
            ['nome' => '18° TLC Ap Grajau', 'nucleo' => 'Núcleo Ap Grajau', 'funcao' => 'Intercessão'],
            ['nome' => '19° TLC Ap Grajau', 'nucleo' => 'Núcleo Ap Grajau', 'funcao' => 'Música'],
            ['nome' => '20° TLC Ap Grajau', 'nucleo' => 'Núcleo Ap Grajau', 'funcao' => 'Música'],

            // Igreja Verde
            ['nome' => '4° TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '4° Mini TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '5° Mini TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Cozinha'],
            ['nome' => '5° TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '6° TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '6° Mini TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '7° Mini TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '7° TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],
            ['nome' => '8° TLC Igreja Verde', 'nucleo' => 'Núcleo Igreja Verde', 'funcao' => 'Música'],

            // Consolação
            ['nome' => '2° TLC Consolação', 'nucleo' => 'Núcleo Consolação', 'funcao' => 'Música'],

            // Santa Clara
            ['nome' => '3° TLC Santa Clara', 'nucleo' => 'Núcleo Santa Clara', 'funcao' => 'Música'],
            ['nome' => '3° TLC Santa Clara - EQR', 'nucleo' => 'Núcleo Santa Clara', 'funcao' => 'EQR'],

            // Santa Terezinha
            ['nome' => '7° TLC Santa Terezinha', 'nucleo' => 'Núcleo Santa Terezinha', 'funcao' => 'Música'],

            // Rainha
            ['nome' => '7° TLC Rainha', 'nucleo' => 'Núcleo Rainha', 'funcao' => 'EQR'],
            ['nome' => '10° TLC Rainha', 'nucleo' => 'Núcleo Rainha', 'funcao' => 'Música'],
            ['nome' => '11° TLC Rainha', 'nucleo' => 'Núcleo Rainha', 'funcao' => 'Intercessão'],

            // São José
            ['nome' => '21° TLC São Jose', 'nucleo' => 'Núcleo São José', 'funcao' => 'EQR'],

            // TLC de Pais
            ['nome' => '15° TLC de Pais', 'nucleo' => 'Núcleo TLC de Pais', 'funcao' => 'Música'],
            ['nome' => '17° TLC de Pais', 'nucleo' => 'Núcleo TLC de Pais', 'funcao' => 'Música'],
            ['nome' => '21° TLC de Pais', 'nucleo' => 'Núcleo TLC de Pais', 'funcao' => 'Intercessão'],

            // São Bento
            ['nome' => '5° TLC São Bento', 'nucleo' => 'Núcleo São Bento', 'funcao' => 'Música'],

            // Santa Rita
            ['nome' => '5° TLC Santa Rita', 'nucleo' => 'Núcleo Santa Rita', 'funcao' => 'Intercessão'],

            // São Bernardo
            ['nome' => '1° Mini TLC São Bernardo', 'nucleo' => 'Núcleo São Bernardo', 'funcao' => 'Música'],
            ['nome' => '2° Mini TLC São Bernardo', 'nucleo' => 'Núcleo São Bernardo', 'funcao' => 'Intercessão'],

            // Ap Miriam
            ['nome' => '10° TLC Ap Miriam', 'nucleo' => 'Núcleo Ap Miriam', 'funcao' => 'Intercessão'],

            // São Sebastião
            ['nome' => '1° TLC São Sebastião', 'nucleo' => 'Núcleo São Sebastião', 'funcao' => 'Intercessão'],

            // Medalha Milagrosa
            ['nome' => '1° TLC Medalha Milagrosa', 'nucleo' => 'Núcleo Medalha Milagrosa', 'funcao' => 'EQR'],

            // Areado
            ['nome' => '1° TLC de Pais de Areado', 'diocese' => 'Diocese de Areado', 'funcao' => 'Música'],

            // Santo Arnaldo
            ['nome' => '2° TLC Santo Arnaldo', 'nucleo' => 'Núcleo Santo Arnaldo', 'funcao' => 'Música'],
        ];

        foreach ($eventosFuncoes as $item) {
            $evento = $this->findEvento($item);
            if (!$evento) {
                continue;
            }

            $funcao = FuncaoDirigente::where('nome', $item['funcao'])->first();
            if (!$funcao) {
                continue;
            }

            // Criar ou atualizar participação
            $participacao = EventoParticipante::where('evento_id', $evento->id)
                ->where('dirigente_id', $fernando->id)
                ->first();

            if ($participacao) {
                $participacao->update([
                    'funcao_dirigente_id' => $funcao->id,
                    'tipo_participante' => 'dirigente'
                ]);
            } else {
                EventoParticipante::create([
                    'evento_id' => $evento->id,
                    'dirigente_id' => $fernando->id,
                    'funcao_dirigente_id' => $funcao->id,
                    'tipo_participante' => 'dirigente',
                    'presenca' => false,
                ]);
            }
        }
    }

    private function findEvento($item)
    {
        if (isset($item['nucleo'])) {
            return Evento::whereHas('entidadeCriadora', function ($query) use ($item) {
                $query->where('nome', $item['nucleo']);
            })->where('nome', $item['nome'])->first();
        } elseif (isset($item['diocese'])) {
            return Evento::whereHas('entidadeCriadora', function ($query) use ($item) {
                $query->where('nome', $item['diocese']);
            })->where('nome', $item['nome'])->first();
        }
        return null;
    }
}
