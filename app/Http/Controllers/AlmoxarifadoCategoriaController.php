<?php

namespace App\Http\Controllers;

use App\Models\AlmoxarifadoCategoria;
use App\Http\Requests\StoreAlmoxarifadoCategoriaRequest;
use App\Http\Requests\UpdateAlmoxarifadoCategoriaRequest;
use Illuminate\Http\Request;

class AlmoxarifadoCategoriaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = AlmoxarifadoCategoria::query();

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        $categorias = $query->paginate(15);
        return view('almoxarifado.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return redirect()->route('almoxarifado-categorias.index');
    }

    public function store(StoreAlmoxarifadoCategoriaRequest $request)
    {
        $this->authorize('create', AlmoxarifadoCategoria::class);

        AlmoxarifadoCategoria::create($request->validated());

        return response()->json([
            'message' => 'Categoria criada com sucesso!',
        ], 201);
    }

    public function show(AlmoxarifadoCategoria $categoria)
    {
        $this->authorize('view', $categoria);
        return view('almoxarifado.categorias.show', compact('categoria'));
    }

    public function edit(AlmoxarifadoCategoria $categoria)
    {
        $this->authorize('update', $categoria);
        return redirect()->route('almoxarifado-categorias.show', $categoria);
    }

    public function update(UpdateAlmoxarifadoCategoriaRequest $request, AlmoxarifadoCategoria $categoria)
    {
        $this->authorize('update', $categoria);

        $categoria->update($request->validated());

        return response()->json([
            'message' => 'Categoria atualizada com sucesso!',
        ]);
    }

    public function destroy(AlmoxarifadoCategoria $categoria)
    {
        $this->authorize('delete', $categoria);

        $categoria->delete();

        return response()->json([
            'message' => 'Categoria deletada com sucesso!',
        ]);
    }
}
