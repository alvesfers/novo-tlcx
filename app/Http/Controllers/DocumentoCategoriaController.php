<?php

namespace App\Http\Controllers;

use App\Models\DocumentoCategoria;
use App\Http\Requests\StoreDocumentoCategoriaRequest;
use App\Http\Requests\UpdateDocumentoCategoriaRequest;

class DocumentoCategoriaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = DocumentoCategoria::query();

        if ($user->tipo_usuario !== 'admin' && $user->entidade) {
            $query->where('entidade_id', $user->entidade->id);
        }

        $categorias = $query->paginate(15);
        return view('documentos.categorias.index', compact('categorias'));
    }

    public function create()
    {
        return redirect()->route('documento-categorias.index');
    }

    public function store(StoreDocumentoCategoriaRequest $request)
    {
        $this->authorize('create', DocumentoCategoria::class);

        DocumentoCategoria::create($request->validated());

        return response()->json([
            'message' => 'Categoria criada com sucesso!',
        ], 201);
    }

    public function update(UpdateDocumentoCategoriaRequest $request, DocumentoCategoria $categoria)
    {
        $this->authorize('update', $categoria);

        $categoria->update($request->validated());

        return response()->json([
            'message' => 'Categoria atualizada com sucesso!',
        ]);
    }

    public function destroy(DocumentoCategoria $categoria)
    {
        $this->authorize('delete', $categoria);

        $categoria->delete();

        return response()->json([
            'message' => 'Categoria deletada com sucesso!',
        ]);
    }
}
