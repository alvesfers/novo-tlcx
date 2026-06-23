<?php

namespace App\Http\Controllers;

use App\Models\Evento;

class EventoDirigentesPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('participantes_dirigentes')) {
            return redirect()->route('eventos.show', $evento);
        }

        return view('eventos.modulos.dirigentes', compact('evento'));
    }
}
