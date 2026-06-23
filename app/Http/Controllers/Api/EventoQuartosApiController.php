<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoQuartosApiController extends Controller
{
    public function adicionar(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'ala_id' => 'required|integer',
            'quarto_id' => 'required|integer',
            'participante_id' => 'required|exists:evento_participantes,id',
        ]);

        $quartos = $evento->quartos ?? [];

        if (!isset($quartos[$validated['ala_id']])) {
            $quartos[$validated['ala_id']] = [];
        }

        if (!isset($quartos[$validated['ala_id']][$validated['quarto_id']])) {
            $quartos[$validated['ala_id']][$validated['quarto_id']] = [];
        }

        if (!in_array($validated['participante_id'], $quartos[$validated['ala_id']][$validated['quarto_id']])) {
            $quartos[$validated['ala_id']][$validated['quarto_id']][] = $validated['participante_id'];
        }

        $evento->update(['quartos' => $quartos]);

        return response()->json([
            'success' => true,
            'message' => 'Participante adicionado ao quarto!',
        ]);
    }

    public function remover(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'ala_id' => 'required|integer',
            'quarto_id' => 'required|integer',
            'participante_id' => 'required|string',
        ]);

        $quartos = $evento->quartos ?? [];

        if (isset($quartos[$validated['ala_id']][$validated['quarto_id']])) {
            $quartos[$validated['ala_id']][$validated['quarto_id']] = array_values(
                array_filter(
                    $quartos[$validated['ala_id']][$validated['quarto_id']],
                    fn($id) => $id !== (int) $validated['participante_id']
                )
            );
        }

        $evento->update(['quartos' => $quartos]);

        return response()->json([
            'success' => true,
            'message' => 'Participante removido do quarto!',
        ]);
    }
}
