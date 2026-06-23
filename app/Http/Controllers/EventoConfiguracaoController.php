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

        $validatedModulos = $request->validate([
            'modulos' => 'array',
            'modulos.*' => 'string',
        ])['modulos'] ?? [];

        // Converter para array de flags
        $modulosHabilitados = [];
        foreach (ModulosEvento::cases() as $modulo) {
            $modulosHabilitados[$modulo->value] = in_array($modulo->value, $validatedModulos);
        }

        $evento->update(['modulos_habilitados' => $modulosHabilitados]);

        return redirect()->route('eventos.show', $evento)
            ->with('success', 'Configurações atualizadas com sucesso!');
    }
}
