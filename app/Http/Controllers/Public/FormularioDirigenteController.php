<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Dirigente;
use App\Models\EventoParticipante;
use Illuminate\Http\Request;

class FormularioDirigenteController extends Controller
{
    public function show($eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        $formulario = $evento->formulario_dirigentes ?? [];

        return view('public.formulario-dirigente', compact('evento', 'formulario'));
    }

    public function enviar(Request $request, $eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        $formulario = $evento->formulario_dirigentes ?? [];
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

        // Criar ou obter dirigente
        $nomeField = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($formulario[0]['nome'] ?? 'nome'));
        $nome = $validated[$nomeField] ?? 'Dirigente';

        $emailField = null;
        foreach ($formulario as $campo) {
            if ($campo['tipo'] === 'email') {
                $emailField = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($campo['nome']));
                break;
            }
        }
        $email = $emailField ? ($validated[$emailField] ?? null) : null;

        // Buscar ou criar dirigente
        $dirigente = Dirigente::where('nome', $nome)
            ->where('email', $email)
            ->first();

        if (!$dirigente) {
            $dirigente = Dirigente::create([
                'nome' => $nome,
                'email' => $email,
                'data_inicio' => now(),
                'ativo' => true,
            ]);
        }

        // Criar ou atualizar evento_participante com respostas
        $eventoParticipante = EventoParticipante::where('evento_id', $evento->id)
            ->where('dirigente_id', $dirigente->id)
            ->where('tipo_participante', 'dirigente')
            ->first();

        if ($eventoParticipante) {
            // Atualizar respostas se já existir
            $eventoParticipante->update([
                'respostas_formulario' => $validated,
            ]);
        } else {
            // Criar novo registro
            EventoParticipante::create([
                'evento_id' => $evento->id,
                'dirigente_id' => $dirigente->id,
                'tipo_participante' => 'dirigente',
                'presenca' => false,
                'respostas_formulario' => $validated,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Formulário enviado com sucesso! Obrigado por participar.',
            'redirect' => route('evento.formulario.sucesso.dirigente', $eventoUuid),
        ]);
    }

    public function sucesso($eventoUuid)
    {
        $evento = Evento::where('uuid', $eventoUuid)->firstOrFail();

        return view('public.formulario-sucesso-dirigente', compact('evento'));
    }
}
