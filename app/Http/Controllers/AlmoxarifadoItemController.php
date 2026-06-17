<?php

namespace App\Http\Controllers;

use App\Models\AlmoxarifadoItem;
use App\Models\AlmoxarifadoCategoria;
use App\Http\Requests\StoreAlmoxarifadoItemRequest;
use App\Http\Requests\UpdateAlmoxarifadoItemRequest;

class AlmoxarifadoItemController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = AlmoxarifadoItem::with('categoria', 'entidade');

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        $itens = $query->paginate(15);

        $categorias = AlmoxarifadoCategoria::where('entidade_id', $user->entidade_id ?? auth()->user()->entidade->id)->get();

        return view('almoxarifado.itens.index', compact('itens', 'categorias'));
    }

    public function create()
    {
        return redirect()->route('almoxarifado-itens.index');
    }

    public function store(StoreAlmoxarifadoItemRequest $request)
    {
        $this->authorize('create', AlmoxarifadoItem::class);

        AlmoxarifadoItem::create($request->validated());

        return response()->json([
            'message' => 'Item criado com sucesso!',
        ], 201);
    }

    public function show(AlmoxarifadoItem $item)
    {
        $this->authorize('view', $item);
        $movimentos = $item->movimentos()->latest()->paginate(10);
        return view('almoxarifado.itens.show', compact('item', 'movimentos'));
    }

    public function edit(AlmoxarifadoItem $item)
    {
        $this->authorize('update', $item);
        return redirect()->route('almoxarifado-itens.show', $item);
    }

    public function update(UpdateAlmoxarifadoItemRequest $request, AlmoxarifadoItem $item)
    {
        $this->authorize('update', $item);

        $item->update($request->validated());

        return response()->json([
            'message' => 'Item atualizado com sucesso!',
        ]);
    }

    public function destroy(AlmoxarifadoItem $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json([
            'message' => 'Item deletado com sucesso!',
        ]);
    }
}
