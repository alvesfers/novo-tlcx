<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoFormulariosController extends Controller
{
    public function show(Evento $evento)
    {
        $this->authorize('update', $evento);

        $formularioDirigentes = $evento->formulario_dirigentes ?? [];
        $formularioParticipantes = $evento->formulario_participantes ?? [];

        return view('eventos.modulos.formularios', compact('evento', 'formularioDirigentes', 'formularioParticipantes'));
    }

    public function adicionarCampo(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,participantes',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:text,email,number,select,radio,checkbox,textarea,date',
            'obrigatorio' => 'boolean',
            'opcoes' => 'nullable|array',
            'opcoes.*' => 'string|max:255',
        ]);

        $tipoFormulario = $validated['formulario_tipo'];
        $chave = $tipoFormulario === 'dirigentes' ? 'formulario_dirigentes' : 'formulario_participantes';

        $formulario = $evento->$chave ?? [];

        $novoCampo = [
            'id' => uniqid(),
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'tipo' => $validated['tipo'],
            'obrigatorio' => $validated['obrigatorio'] ?? false,
            'opcoes' => ($validated['tipo'] !== 'text' && $validated['tipo'] !== 'email' && $validated['tipo'] !== 'number' && $validated['tipo'] !== 'textarea' && $validated['tipo'] !== 'date')
                ? array_filter($validated['opcoes'] ?? [])
                : null,
        ];

        $formulario[] = $novoCampo;
        $evento->update([$chave => $formulario]);

        return response()->json([
            'success' => true,
            'message' => 'Campo adicionado com sucesso!',
            'formulario' => $formulario,
        ]);
    }

    public function atualizarCampo(Request $request, Evento $evento, $campoId)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,participantes',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:text,email,number,select,radio,checkbox,textarea,date',
            'obrigatorio' => 'boolean',
            'opcoes' => 'nullable|array',
            'opcoes.*' => 'string|max:255',
        ]);

        $tipoFormulario = $validated['formulario_tipo'];
        $chave = $tipoFormulario === 'dirigentes' ? 'formulario_dirigentes' : 'formulario_participantes';

        $formulario = collect($evento->$chave ?? [])
            ->map(function ($campo) use ($campoId, $validated) {
                if ($campo['id'] === $campoId) {
                    return [
                        'id' => $campo['id'],
                        'nome' => $validated['nome'],
                        'descricao' => $validated['descricao'],
                        'tipo' => $validated['tipo'],
                        'obrigatorio' => $validated['obrigatorio'] ?? false,
                        'opcoes' => ($validated['tipo'] !== 'text' && $validated['tipo'] !== 'email' && $validated['tipo'] !== 'number' && $validated['tipo'] !== 'textarea' && $validated['tipo'] !== 'date')
                            ? array_filter($validated['opcoes'] ?? [])
                            : null,
                    ];
                }
                return $campo;
            })
            ->values()
            ->toArray();

        $evento->update([$chave => $formulario]);

        return response()->json([
            'success' => true,
            'message' => 'Campo atualizado com sucesso!',
            'formulario' => $formulario,
        ]);
    }

    public function removerCampo(Request $request, Evento $evento, $campoId)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,participantes',
        ]);

        $tipoFormulario = $validated['formulario_tipo'];
        $chave = $tipoFormulario === 'dirigentes' ? 'formulario_dirigentes' : 'formulario_participantes';

        $formulario = collect($evento->$chave ?? [])
            ->reject(fn($campo) => $campo['id'] === $campoId)
            ->values()
            ->toArray();

        $evento->update([$chave => $formulario]);

        return response()->json([
            'success' => true,
            'message' => 'Campo removido com sucesso!',
            'formulario' => $formulario,
        ]);
    }
}
