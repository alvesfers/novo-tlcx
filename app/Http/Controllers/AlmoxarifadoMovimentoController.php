<?php

namespace App\Http\Controllers;

use App\Models\AlmoxarifadoMovimento;
use App\Http\Requests\StoreAlmoxarifadoMovimentoRequest;
use App\Services\AlmoxarifadoService;

class AlmoxarifadoMovimentoController extends Controller
{
    public function __construct(private AlmoxarifadoService $service) {}

    public function index()
    {
        $user = auth()->user();
        $query = AlmoxarifadoMovimento::with('item', 'entidade', 'responsavel');

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        $movimentos = $query->latest('data_movimento')->paginate(15);
        return view('almoxarifado.movimentos.index', compact('movimentos'));
    }

    public function create()
    {
        return redirect()->route('almoxarifado-movimentos.index');
    }

    public function store(StoreAlmoxarifadoMovimentoRequest $request)
    {
        $this->authorize('create', AlmoxarifadoMovimento::class);

        try {
            $item = \App\Models\AlmoxarifadoItem::findOrFail($request->almoxarifado_item_id);

            match ($request->tipo_movimento) {
                'entrada' => $this->service->registrarEntrada($item, $request->quantidade, $request->validated()),
                'saida' => $this->service->registrarSaida($item, $request->quantidade, $request->validated()),
                'ajuste' => $this->service->registrarAjuste($item, $request->quantidade, $request->validated()),
                default => throw new \Exception('Tipo de movimento inválido'),
            };

            return response()->json([
                'message' => 'Movimento registrado com sucesso!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao registrar movimento: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function show(AlmoxarifadoMovimento $movimento)
    {
        $this->authorize('view', $movimento);
        return view('almoxarifado.movimentos.show', compact('movimento'));
    }

    public function destroy(AlmoxarifadoMovimento $movimento)
    {
        $this->authorize('delete', $movimento);

        $movimento->delete();

        return response()->json([
            'message' => 'Movimento deletado com sucesso!',
        ]);
    }
}
