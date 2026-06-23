<?php

namespace App\Http\Controllers;

use App\Models\Evento;

class EventoCronogramaPageController extends Controller
{
    public function index(Evento $evento)
    {
        $this->authorize('view', $evento);

        if (!$evento->isModuloHabilitado('cronograma')) {
            return redirect()->route('eventos.show', $evento);
        }

        return view('eventos.modulos.cronograma', compact('evento'));
    }
}
