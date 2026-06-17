<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dirigente;
use Illuminate\Http\Request;

class DirigenteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Dirigente::with('vinculos.entidade');

        if (!$user->isAdmin()) {
            $query->whereHas('vinculos', function ($q) use ($user) {
                $q->where('entidade_id', $user->entidade_id)
                  ->orWhereIn('entidade_id', function ($q2) use ($user) {
                      $q2->select('id')->from('entidades')->where('entidade_pai_id', $user->entidade_id);
                  });
            });
        }

        $dirigentes = $query->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $dirigentes->items(),
            'meta' => [
                'current_page' => $dirigentes->currentPage(),
                'total' => $dirigentes->total(),
                'per_page' => $dirigentes->perPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'email' => 'nullable|email',
            'data_nascimento' => 'nullable|date',
        ]);

        $dirigente = Dirigente::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $dirigente,
            'message' => 'Dirigente criado com sucesso',
        ], 201);
    }

    public function show(Dirigente $dirigente)
    {
        return response()->json([
            'success' => true,
            'data' => $dirigente->load('vinculos.entidade'),
        ]);
    }

    public function update(Request $request, Dirigente $dirigente)
    {
        $request->validate([
            'nome' => 'string',
            'email' => 'nullable|email',
            'data_nascimento' => 'nullable|date',
        ]);

        $dirigente->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $dirigente,
            'message' => 'Dirigente atualizado com sucesso',
        ]);
    }

    public function destroy(Dirigente $dirigente)
    {
        $dirigente->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dirigente deletado com sucesso',
        ]);
    }

    public function vinculos(Dirigente $dirigente)
    {
        return response()->json([
            'success' => true,
            'data' => $dirigente->load('vinculos.entidade')->vinculos,
        ]);
    }
}
