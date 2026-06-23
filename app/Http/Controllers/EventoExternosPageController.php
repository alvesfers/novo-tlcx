<?php

namespace App\Http\Controllers;

use App\Models\Evento;

class EventoExternosPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('participantes_externos')) {
            return redirect()->route('eventos.show', $evento);
        }

        return view('eventos.modulos.externos', compact('evento'));
    }
}
