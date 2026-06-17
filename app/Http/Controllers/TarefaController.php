<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;
use App\Services\TarefaService;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function __construct(private TarefaService $service) {}

    public function index()
    {
        $user = auth()->user();
        $filter = request('filter', 'todas');

        $query = Tarefa::with('entidade', 'evento', 'categoria', 'responsavelUser', 'responsavelDirigente');

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        if ($filter === 'pendentes') {
            $query->pendentes();
        } elseif ($filter === 'em_andamento') {
            $query->emAndamento();
        } elseif ($filter === 'concluidas') {
            $query->concluidas();
        } elseif ($filter === 'vencidas') {
            $query->vencidas();
        }

        $tarefas = $query->paginate(15);
        return view('tarefas.index', compact('tarefas', 'filter'));
    }

    public function create()
    {
        return redirect()->route('tarefas.index');
    }

    public function store(StoreTarefaRequest $request)
    {
        $this->authorize('create', Tarefa::class);

        $this->service->criar($request->validated());

        return response()->json([
            'message' => 'Tarefa criada com sucesso!',
        ], 201);
    }

    public function show(Tarefa $tarefa)
    {
        $this->authorize('view', $tarefa);
        $comentarios = $tarefa->comentarios()->latest()->get();
        return view('tarefas.show', compact('tarefa', 'comentarios'));
    }

    public function update(UpdateTarefaRequest $request, Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $this->service->atualizar($tarefa, $request->validated());

        return response()->json([
            'message' => 'Tarefa atualizada com sucesso!',
        ]);
    }

    public function destroy(Tarefa $tarefa)
    {
        $this->authorize('delete', $tarefa);

        $tarefa->delete();

        return response()->json([
            'message' => 'Tarefa deletada com sucesso!',
        ]);
    }

    public function concluir(Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $this->service->concluir($tarefa);

        return response()->json([
            'message' => 'Tarefa concluída com sucesso!',
        ]);
    }

    public function cancelar(Tarefa $tarefa)
    {
        $this->authorize('update', $tarefa);

        $this->service->cancelar($tarefa);

        return response()->json([
            'message' => 'Tarefa cancelada com sucesso!',
        ]);
    }
}
