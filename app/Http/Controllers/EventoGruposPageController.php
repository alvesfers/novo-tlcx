<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoGruposPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        $grupos = $evento->grupos ?? [];
        $externos = $evento->participantes->where('tipo_participante', 'externo');
        $dirigentes = $evento->participantes->where('tipo_participante', 'dirigente');

        return view('eventos.modulos.grupos', compact('evento', 'grupos', 'externos', 'dirigentes'));
    }

    public function store(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'dirigente_id' => 'nullable|exists:evento_participantes,id',
            'descricao' => 'nullable|string',
        ]);

        $grupos = $evento->grupos ?? [];
        $novoGrupo = array_merge($validated, [
            'id' => uniqid(),
            'participantes' => [],
            'created_at' => now()->toIso8601String(),
        ]);

        $grupos[] = $novoGrupo;
        $evento->update(['grupos' => $grupos]);

        return response()->json([
            'success' => true,
            'message' => 'Grupo criado com sucesso!',
            'grupos' => $grupos,
        ]);
    }

    public function adicionarParticipante(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'grupo_id' => 'required|string',
            'participante_id' => 'required|exists:evento_participantes,id',
        ]);

        $grupos = $evento->grupos ?? [];

        foreach ($grupos as &$grupo) {
            if ($grupo['id'] === $validated['grupo_id']) {
                if (!isset($grupo['participantes'])) {
                    $grupo['participantes'] = [];
                }
                if (!in_array($validated['participante_id'], $grupo['participantes'])) {
                    $grupo['participantes'][] = $validated['participante_id'];
                }
                break;
            }
        }

        $evento->update(['grupos' => $grupos]);

        return response()->json([
            'success' => true,
            'message' => 'Participante adicionado ao grupo!',
        ]);
    }

    public function removerParticipante(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'grupo_id' => 'required|string',
            'participante_id' => 'required|string',
        ]);

        $grupos = $evento->grupos ?? [];

        foreach ($grupos as &$grupo) {
            if ($grupo['id'] === $validated['grupo_id']) {
                $grupo['participantes'] = array_values(
                    array_filter($grupo['participantes'], fn($id) => $id !== $validated['participante_id'])
                );
                break;
            }
        }

        $evento->update(['grupos' => $grupos]);

        return response()->json([
            'success' => true,
            'message' => 'Participante removido do grupo!',
        ]);
    }

    public function deletar(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'grupo_id' => 'required|string',
        ]);

        $grupos = $evento->grupos ?? [];
        $grupos = array_values(array_filter($grupos, fn($g) => $g['id'] !== $validated['grupo_id']));

        $evento->update(['grupos' => $grupos]);

        return response()->json([
            'success' => true,
            'message' => 'Grupo deletado com sucesso!',
        ]);
    }
}
