<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Evento::with(['criadora', 'entidades', 'participantes']);

        if (!$user->isAdmin()) {
            $query->where('entidade_criadora_id', $user->entidade_id)
                  ->orWhereHas('entidades', function ($q) use ($user) {
                      $q->where('entidade_id', $user->entidade_id);
                  });
        }

        $eventos = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $eventos->items(),
            'meta' => [
                'current_page' => $eventos->currentPage(),
                'total' => $eventos->total(),
                'per_page' => $eventos->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'local' => 'nullable|string',
            'descricao' => 'nullable|string',
        ]);

        $evento = Evento::create(array_merge(
            $request->validated(),
            ['entidade_criadora_id' => auth()->user()->entidade_id]
        ));

        return response()->json([
            'success' => true,
            'data' => $evento,
            'message' => 'Evento criado com sucesso',
        ], 201);
    }

    public function show(Evento $evento)
    {
        return response()->json([
            'success' => true,
            'data' => $evento->load(['criadora', 'entidades', 'participantes']),
        ]);
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nome' => 'string',
            'data_inicio' => 'date',
            'data_fim' => 'date',
            'local' => 'nullable|string',
            'descricao' => 'nullable|string',
        ]);

        $evento->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $evento,
            'message' => 'Evento atualizado com sucesso',
        ]);
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento deletado com sucesso',
        ]);
    }

    public function participar(Request $request, Evento $evento)
    {
        $request->validate([
            'dirigente_id' => 'required|exists:dirigentes,id',
        ]);

        $evento->participantes()->create([
            'dirigente_id' => $request->dirigente_id,
            'presenca' => 'pendente',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscrição realizada com sucesso',
        ], 201);
    }

    public function checkin(Request $request, Evento $evento)
    {
        $request->validate([
            'dirigente_id' => 'required|exists:dirigentes,id',
        ]);

        $participante = $evento->participantes()
            ->where('dirigente_id', $request->dirigente_id)
            ->firstOrFail();

        $participante->update([
            'presenca' => 'confirmado',
            'checkin_em' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in realizado com sucesso',
        ]);
    }
}
