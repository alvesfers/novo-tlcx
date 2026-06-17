<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Http\Requests\StoreDocumentoRequest;
use App\Http\Requests\UpdateDocumentoRequest;
use App\Services\DocumentoService;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function __construct(private DocumentoService $service) {}

    public function index()
    {
        $user = auth()->user();
        $filter = request('filter', 'todos');

        $documentos = $this->service->getDocumentosVisivelQuery($user);

        if ($filter === 'publicos') {
            $documentos = $documentos->where('visibilidade', 'publico');
        } elseif ($filter === 'privados') {
            $documentos = $documentos->where('visibilidade', 'privado');
        }

        $documentos = $documentos->paginate(15);

        return view('documentos.index', compact('documentos', 'filter'));
    }

    public function create()
    {
        return redirect()->route('documentos.index');
    }

    public function store(StoreDocumentoRequest $request)
    {
        $this->authorize('create', Documento::class);

        try {
            $this->service->upload(
                $request->except('arquivo'),
                $request->file('arquivo')
            );

            return response()->json([
                'message' => 'Documento enviado com sucesso!',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao enviar documento: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function show(Documento $documento)
    {
        $this->authorize('view', $documento);
        return view('documentos.show', compact('documento'));
    }

    public function update(UpdateDocumentoRequest $request, Documento $documento)
    {
        $this->authorize('update', $documento);

        try {
            $this->service->atualizar(
                $documento,
                $request->except('arquivo'),
                $request->file('arquivo')
            );

            return response()->json([
                'message' => 'Documento atualizado com sucesso!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar documento: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(Documento $documento)
    {
        $this->authorize('delete', $documento);

        $this->service->excluir($documento);

        return response()->json([
            'message' => 'Documento deletado com sucesso!',
        ]);
    }

    public function download(Documento $documento)
    {
        $this->authorize('download', $documento);

        if ($documento->visibilidade === 'privado') {
            return Storage::disk('local')->download($documento->arquivo_path, $documento->arquivo_nome_original);
        } else {
            return Storage::disk('public')->download($documento->arquivo_path, $documento->arquivo_nome_original);
        }
    }
}
