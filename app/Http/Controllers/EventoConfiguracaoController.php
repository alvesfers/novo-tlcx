<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Enums\ModulosEvento;
use Illuminate\Http\Request;

class EventoConfiguracaoController extends Controller
{
    public function show(Evento $evento)
    {
        $this->authorize('update', $evento);

        $modulos = ModulosEvento::cases();
        $modulosHabilitados = $evento->modulos_habilitados ?? [];

        return view('eventos.configuracao', compact('evento', 'modulos', 'modulosHabilitados'));
    }

    public function update(Request $request, Evento $evento)
    {
        $this->authorize('update', $evento);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tema' => 'nullable|string|max:255',
            'local' => 'nullable|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|string|in:rascunho,publicado,encerrado,cancelado',
            'ativo' => 'boolean',
            'modulos' => 'array',
            'modulos.*' => 'string',
        ]);

        // Converter módulos para array de flags
        $validatedModulos = $request->input('modulos', []);
        $modulosHabilitados = [];
        foreach (ModulosEvento::cases() as $modulo) {
            $modulosHabilitados[$modulo->value] = in_array($modulo->value, $validatedModulos);
        }

        // Preparar dados para salvar
        $dataToUpdate = [
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'tema' => $validated['tema'],
            'local' => $validated['local'],
            'data_inicio' => $validated['data_inicio'],
            'data_fim' => $validated['data_fim'],
            'status' => $validated['status'],
            'ativo' => $validated['ativo'] ?? true,
            'modulos_habilitados' => $modulosHabilitados,
        ];

        $evento->update($dataToUpdate);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Configurações do evento atualizadas com sucesso!');
    }
}
