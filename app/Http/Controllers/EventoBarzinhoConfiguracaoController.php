<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Barzinho;
use App\Enums\TipoBarzinho;
use Illuminate\Http\Request;

class EventoBarzinhoConfiguracaoController extends Controller
{
    public function show(Evento $evento)
    {
        $this->authorize('update', $evento);

        $tipos = TipoBarzinho::cases();
        $barzinho = $evento->barzinho;

        return view('eventos.barzinho-configuracao', compact('evento', 'tipos', 'barzinho'));
    }

    public function update(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_venda' => 'required|string|in:consumo_depois,paga_na_hora,pre_pago',
            'ativo' => 'boolean',
            'config' => 'nullable|json',
        ]);

        if ($evento->barzinho) {
            $evento->barzinho->update([
                'nome' => $validated['nome'],
                'descricao' => $validated['descricao'],
                'tipo_venda' => $validated['tipo_venda'],
                'barzinho_config' => $validated['config'],
                'ativo' => $validated['ativo'] ?? true,
            ]);
        } else {
            $evento->barzinho()->create([
                'nome' => $validated['nome'],
                'descricao' => $validated['descricao'],
                'tipo_venda' => $validated['tipo_venda'],
                'barzinho_config' => $validated['config'],
                'ativo' => $validated['ativo'] ?? true,
            ]);
        }

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Configuração do barzinho atualizada!');
    }
}
