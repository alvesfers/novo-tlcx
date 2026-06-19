<?php

namespace App\Http\Controllers;

use App\Models\AlasRetiro;
use App\Models\CasasDeRetiro;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlasRetiroController extends Controller
{
    public function index($casaRetiro): View
    {
        $casasDeRetiro = CasasDeRetiro::findOrFail($casaRetiro);
        $alas = $casasDeRetiro->alas()->paginate(15);

        return view('alas-retiro.index', compact('casasDeRetiro', 'alas'));
    }

    public function store(Request $request, $casaRetiro)
    {
        $casasDeRetiro = CasasDeRetiro::findOrFail($casaRetiro);

        $validated = $request->validate([
            'nome_ala' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:500',
        ]);

        $validated['id_casa'] = $casasDeRetiro->id_casa;
        $ala = AlasRetiro::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ala criada com sucesso!',
            'ala' => $ala,
        ]);
    }

    public function update(Request $request, $casaRetiro, AlasRetiro $alasRetiro)
    {
        $validated = $request->validate([
            'nome_ala' => 'required|string|max:100',
            'descricao' => 'nullable|string|max:500',
        ]);

        $alasRetiro->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ala atualizada com sucesso!',
            'ala' => $alasRetiro,
        ]);
    }

    public function destroy($casaRetiro, AlasRetiro $alasRetiro)
    {
        $alasRetiro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ala deletada com sucesso!',
        ]);
    }
}
