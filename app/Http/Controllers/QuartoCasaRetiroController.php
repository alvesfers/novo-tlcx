<?php

namespace App\Http\Controllers;

use App\Models\CasasDeRetiro;
use App\Models\QuartosCasasDeRetiro;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuartoCasaRetiroController extends Controller
{
    public function index($casaRetiro): View
    {
        $casasDeRetiro = CasasDeRetiro::findOrFail($casaRetiro);
        $quartos = $casasDeRetiro->quartos()->paginate(15);

        return view('quartos-casa-retiro.index', compact('casasDeRetiro', 'quartos'));
    }

    public function store(Request $request, CasasDeRetiro $casasDeRetiro)
    {
        $validated = $request->validate([
            'numero_quarto' => 'required|string|max:50',
            'vagas' => 'required|integer|min:1',
            'banheiros' => 'nullable|integer|min:0',
            'acessibilidade' => 'nullable|boolean',
        ]);

        $quarto = $casasDeRetiro->quartos()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quarto criado com sucesso!',
            'quarto' => $quarto,
        ]);
    }

    public function update(Request $request, CasasDeRetiro $casasDeRetiro, QuartosCasasDeRetiro $quartosCasasDeRetiro)
    {
        $validated = $request->validate([
            'numero_quarto' => 'required|string|max:50',
            'vagas' => 'required|integer|min:1',
            'banheiros' => 'nullable|integer|min:0',
            'acessibilidade' => 'nullable|boolean',
        ]);

        $quartosCasasDeRetiro->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Quarto atualizado com sucesso!',
            'quarto' => $quartosCasasDeRetiro,
        ]);
    }

    public function destroy(CasasDeRetiro $casasDeRetiro, QuartosCasasDeRetiro $quartosCasasDeRetiro)
    {
        $quartosCasasDeRetiro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Quarto deletado com sucesso!',
        ]);
    }
}
