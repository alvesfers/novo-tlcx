<?php

namespace App\Http\Controllers;

use App\Models\TarefaCategoria;
use App\Http\Requests\StoreTarefaCategoriaRequest;
use App\Http\Requests\UpdateTarefaCategoriaRequest;

class TarefaCategoriaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = TarefaCategoria::query();

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        $categorias = $query->paginate(15);
        return view('tarefas.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return redirect()->route('tarefa-categorias.index');
    }

    public function store(StoreTarefaCategoriaRequest $request)
    {
        $this->authorize('create', TarefaCategoria::class);

        TarefaCategoria::create($request->validated());

        return response()->json([
            'message' => 'Categoria criada com sucesso!',
        ], 201);
    }

    public function update(UpdateTarefaCategoriaRequest $request, TarefaCategoria $categoria)
    {
        $this->authorize('update', $categoria);

        $categoria->update($request->validated());

        return response()->json([
            'message' => 'Categoria atualizada com sucesso!',
        ]);
    }

    public function destroy(TarefaCategoria $categoria)
    {
        $this->authorize('delete', $categoria);

        $categoria->delete();

        return response()->json([
            'message' => 'Categoria deletada com sucesso!',
        ]);
    }
}
