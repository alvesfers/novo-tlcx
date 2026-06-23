<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\ParticipanteExterno;
use App\Models\EventoParticipante;
use Illuminate\Http\Request;

class FormularioParticipanteController extends Controller
{
    public function show($eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        $formulario = $evento->formulario_participantes ?? [];

        return view('public.formulario-participante', compact('evento', 'formulario'));
    }

    public function enviar(Request $request, $eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        $formulario = $evento->formulario_participantes ?? [];
        $regras = [];

        // Gerar regras de validação dinâmicas
        foreach ($formulario as $campo) {
            $campoNome = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($campo['nome']));

            if ($campo['obrigatorio']) {
                $regras[$campoNome] = 'required';
            } else {
                $regras[$campoNome] = 'nullable';
            }

            // Adicionar tipo de validação
            if ($campo['tipo'] === 'email') {
                $regras[$campoNome] .= '|email';
            } elseif ($campo['tipo'] === 'number') {
                $regras[$campoNome] .= '|numeric';
            } elseif ($campo['tipo'] === 'date') {
                $regras[$campoNome] .= '|date';
            }

            $regras[$campoNome] = array_filter(explode('|', $regras[$campoNome]));
        }

        $validated = $request->validate($regras);

        // Criar ou obter participante externo
        $nomeField = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($formulario[0]['nome'] ?? 'nome'));
        $nome = $validated[$nomeField] ?? 'Participante';

        $emailField = null;
        foreach ($formulario as $campo) {
            if ($campo['tipo'] === 'email') {
                $emailField = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($campo['nome']));
                break;
            }
        }
        $email = $emailField ? ($validated[$emailField] ?? null) : null;

        // Buscar ou criar participante externo
        $participanteExterno = ParticipanteExterno::where('nome', $nome)
            ->where('email', $email)
            ->first();

        if (!$participanteExterno) {
            $participanteExterno = ParticipanteExterno::create([
                'nome' => $nome,
                'email' => $email,
                'respostas_formulario' => $validated,
            ]);
        } else {
            $participanteExterno->update([
                'respostas_formulario' => $validated,
            ]);
        }

        // Criar evento_participante se não existir
        $eventoParticipante = EventoParticipante::where('evento_id', $evento->id)
            ->where('participante_externo_id', $participanteExterno->id)
            ->where('tipo_participante', 'externo')
            ->first();

        if (!$eventoParticipante) {
            EventoParticipante::create([
                'evento_id' => $evento->id,
                'participante_externo_id' => $participanteExterno->id,
                'tipo_participante' => 'externo',
                'presenca' => false,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Formulário enviado com sucesso! Obrigado por participar.',
            'redirect' => route('evento.formulario.sucesso', $eventoUuid),
        ]);
    }

    public function sucesso($eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        return view('public.formulario-sucesso', compact('evento'));
    }
}
