<?php

namespace App\Http\Controllers;

use App\Models\CasasDeRetiro;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CasaRetiroController extends Controller
{
    public function index(): View
    {
        $casas = CasasDeRetiro::with('quartos')->paginate(15);
        return view('casas-retiro.index', compact('casas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_casa' => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
            'valor_estimado' => 'nullable|numeric|min:0',
            'capacidade' => 'nullable|integer|min:0',
            'acessibilidade' => 'nullable|boolean',
            'ativa' => 'nullable|boolean',
        ]);

        $casa = CasasDeRetiro::create($validated);

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Casa de retiro criada com sucesso!',
                'casa' => $casa,
            ]);
        }

        return redirect()->route('casas-retiro.index')
            ->with('success', 'Casa de retiro criada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $casasDeRetiro = CasasDeRetiro::findOrFail($id);

        $validated = $request->validate([
            'nome_casa' => 'required|string|max:255',
            'endereco' => 'nullable|string|max:255',
            'valor_estimado' => 'nullable|numeric|min:0',
            'capacidade' => 'nullable|integer|min:0',
            'acessibilidade' => 'nullable|boolean',
            'ativa' => 'nullable|boolean',
        ]);

        $casasDeRetiro->update($validated);

        if ($request->header('Accept') === 'application/json' || $request->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Casa de retiro atualizada com sucesso!',
                'casa' => $casasDeRetiro,
            ]);
        }

        return redirect()->route('casas-retiro.index')
            ->with('success', 'Casa de retiro atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $casasDeRetiro = CasasDeRetiro::findOrFail($id);
        $casasDeRetiro->delete();

        if (request()->header('Accept') === 'application/json' || request()->isJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Casa de retiro deletada com sucesso!'
            ]);
        }

        return redirect()->route('casas-retiro.index')
            ->with('success', 'Casa de retiro deletada com sucesso!');
    }
}
