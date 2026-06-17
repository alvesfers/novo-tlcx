<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTipoEventoRequest;
use App\Http\Requests\UpdateTipoEventoRequest;
use App\Models\TipoEvento;
use App\Traits\BulkDeleteable;
use Illuminate\Http\Request;

class TipoEventoController extends Controller
{
    use BulkDeleteable;
    public function index()
    {
        $tiposEvento = TipoEvento::paginate(15);
        return view('tipo-eventos.index', compact('tiposEvento'));
    }

    public function create()
    {
        return redirect()->route('tipo-eventos.index');
    }

    public function store(StoreTipoEventoRequest $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem criar tipos de eventos');
        }

        try {
            $tipoEvento = TipoEvento::create($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de evento criado com sucesso!',
                    'tipoEvento' => $tipoEvento,
                ]);
            }

            return redirect()->route('tipo-eventos.index')
                ->with('success', 'Tipo de evento criado com sucesso');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function show(TipoEvento $tipoEvento)
    {
        return view('tipo-eventos.show', compact('tipoEvento'));
    }

    public function edit(TipoEvento $tipoEvento)
    {
        return redirect()->route('tipo-eventos.index');
    }

    public function update(UpdateTipoEventoRequest $request, TipoEvento $tipoEvento)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem editar tipos de eventos');
        }

        try {
            $tipoEvento->update($request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de evento atualizado com sucesso!',
                    'tipoEvento' => $tipoEvento,
                ]);
            }

            return redirect()->route('tipo-eventos.show', $tipoEvento)
                ->with('success', 'Tipo de evento atualizado com sucesso');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors(),
                    'message' => 'Erro na validação dos dados',
                ], 422);
            }
            throw $e;
        }
    }

    public function destroy(TipoEvento $tipoEvento)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Apenas administradores podem deletar tipos de eventos');
        }

        $tipoEvento->delete();

        return redirect()->route('tipo-eventos.index')
            ->with('success', 'Tipo de evento deletado com sucesso');
    }

    protected function getModel()
    {
        return TipoEvento::class;
    }
}
