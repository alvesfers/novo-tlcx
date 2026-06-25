<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoFormulariosController extends Controller
{
    // Mapeamento tipo → chave do campo no evento
    private const CHAVES = [
        'dirigentes'         => 'formulario_dirigentes',
        'dirigentes_interno' => 'formulario_dirigentes_interno',
        'dirigentes_externo' => 'formulario_dirigentes_externo',
        'participantes'      => 'formulario_participantes',
    ];

    public function show(Evento $evento)
    {
        $this->authorize('update', $evento);

        return view('eventos.modulos.formularios', [
            'evento'                      => $evento,
            'formularioDirigentes'        => $evento->formulario_dirigentes ?? [],
            'formularioDirigentesInterno' => $evento->formulario_dirigentes_interno ?? [],
            'formularioDirigentesExterno' => $evento->formulario_dirigentes_externo ?? [],
            'formularioParticipantes'     => $evento->formulario_participantes ?? [],
        ]);
    }

    public function adicionarCampo(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,dirigentes_interno,dirigentes_externo,participantes',
            'nome'       => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'tipo'       => 'required|in:text,email,number,select,radio,checkbox,textarea,date',
            'obrigatorio' => 'boolean',
            'opcoes'     => 'nullable|array',
            'opcoes.*'   => 'string|max:255',
        ]);

        $chave     = self::CHAVES[$validated['formulario_tipo']];
        $formulario = $evento->$chave ?? [];

        $formulario[] = $this->buildCampo($validated);

        $evento->update([$chave => $formulario]);

        return response()->json(['success' => true, 'formulario' => $formulario]);
    }

    public function atualizarCampo(Request $request, Evento $evento, string $campoId)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,dirigentes_interno,dirigentes_externo,participantes',
            'nome'       => 'required|string|max:255',
            'descricao'  => 'nullable|string',
            'tipo'       => 'required|in:text,email,number,select,radio,checkbox,textarea,date',
            'obrigatorio' => 'boolean',
            'opcoes'     => 'nullable|array',
            'opcoes.*'   => 'string|max:255',
        ]);

        $chave = self::CHAVES[$validated['formulario_tipo']];

        $formulario = collect($evento->$chave ?? [])
            ->map(fn($c) => $c['id'] === $campoId ? $this->buildCampo($validated, $c['id']) : $c)
            ->values()
            ->toArray();

        $evento->update([$chave => $formulario]);

        return response()->json(['success' => true, 'formulario' => $formulario]);
    }

    public function removerCampo(Request $request, Evento $evento, string $campoId)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'formulario_tipo' => 'required|in:dirigentes,dirigentes_interno,dirigentes_externo,participantes',
        ]);

        $chave = self::CHAVES[$validated['formulario_tipo']];

        $formulario = collect($evento->$chave ?? [])
            ->reject(fn($c) => $c['id'] === $campoId)
            ->values()
            ->toArray();

        $evento->update([$chave => $formulario]);

        return response()->json(['success' => true, 'formulario' => $formulario]);
    }

    private function buildCampo(array $data, ?string $id = null): array
    {
        $comOpcoes = in_array($data['tipo'], ['select', 'radio', 'checkbox']);
        return [
            'id'          => $id ?? uniqid(),
            'nome'        => $data['nome'],
            'descricao'   => $data['descricao'] ?? null,
            'tipo'        => $data['tipo'],
            'obrigatorio' => $data['obrigatorio'] ?? false,
            'opcoes'      => $comOpcoes ? array_values(array_filter($data['opcoes'] ?? [])) : null,
        ];
    }
}
